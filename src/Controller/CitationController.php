<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Citation;
use App\Form\CitationType;
use App\Entity\Comment;
use App\Validator\CheckComment;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CitationController extends AbstractController
{
    /**
     * @Route("/citations", name="app_citations")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $em = $this->getDoctrine()->getManager();

        $citations = $paginator->paginate(
            $em->getRepository(Citation::class)->findBy([],["id"=>"desc"]), 
            $request->query->getInt('page', 1), 
            20 
        );
        return $this->render('citation/index.html.twig', [
            'citations' => $citations,
            "route" => "editer_citation"
        ]);
    }

    /**
     * @Route("/citation/{id}", name="app_view_citation")
     */
    public function getOneCitation(Citation $citation = null, Request $request, PaginatorInterface $paginator): Response
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('citation/viewcitation.html.twig', [
            "citation" => $citation
        ]);
    }


    /**
     * @Route("/editer_citation/{id}", name="app_citation")
     */
    public function editOneCitation(Citation $citation = null, Request $request, PaginatorInterface $paginator): Response
    {
        //$this->denyAccessUnlessGranted('CITATION_EDIT', $citation)
        if($citation == null){
            if($this->getUser() == null)return $this->redirect("/");
        }else{
            if(!$this->isGranted('CITATION_EDIT', $citation))return $this->redirect("/");
        }
       
        $em = $this->getDoctrine()->getManager();
        $action = 1;
        if($citation == null){
            $citation = new Citation();
            $citation
                ->setUser($this->getUser())
                ->setCreated(new \DateTime());
            $action = 2;
        }
        $form = $this->createForm(CitationType::class, $citation);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()) {
                $em->persist($citation);
                $em->flush();
                if($action == 2)return $this->redirect("/citations");
                $this->addFlash(
                    'success',
                    'Enregistré!'
                );
            }
        }
        return $this->render('citation/citation.html.twig', [
            'form' => $form->createView(),
            "action" => $action
        ]);
    }

    /**
     * @Route("/deletecitation/{id}", methods="GET")
     */
    public function deleteCitation(Citation $citation = null, Request $request){
        $response = [
            "status" => 0,
            "data" => null
        ];
        if ($request->isXmlHttpRequest()) {
            //$this->denyAccessUnlessGranted('CITATION_EDIT', $citation);
            if($this->isGranted('CITATION_DELETE', $citation)){
                $response["status"] = 1;
                $em = $this->getDoctrine()->getManager();
                $em->remove($citation);
                $em->flush();
            }
        }
        return $this->json($response);
    }

    /**
     * @Route("/editcomment", methods="POST")
     */
    public function editComment(Request $request, ValidatorInterface $validator, CheckComment $constraint){
        if ($request->isXmlHttpRequest()) {
            $data = json_decode($request->getContent(),true);
            $em = $this->getDoctrine()->getManager();
            if($data["id"] == null){
                $comment = new Comment;
                $citation = $em->find(Citation::class,$data["citationid"]);
                $comment
                    ->setUser($this->getUser())
                    ->setCitation($citation)
                    ->setCreated(new \DateTime());
            }else{
                $comment = $em->find(Comment::class,$data["id"]);
                if(!$this->isGranted('COMMENT_EDIT', $comment)){
                    return $this->json(["error" => "Vous n'avez pas le droit à la modification"]);
                }
            }
            $comment->setMessage($data["message"]);

            $errors = $validator->validate(
                $comment->getMessage(),
                $constraint 
            );
            if($errors->count() > 0){
                return $this->json(["error"=>$errors->get(0)->getMessage()]);
            }
            
            $em->persist($comment);
            $em->flush();
        }
        return $this->render('citation/editcomment.html.twig', [
            "comment" => $comment
        ]);
    }

    /**
     * @Route("/deletecomment/{id}", methods="DELETE")
     */
    public function deleteComment(Comment $comment, Request $request){
        $response = [
            "status" => 0,
            "data" => null
        ];
        if ($request->isXmlHttpRequest()) {
            if($this->isGranted('COMMENT_DELETE', $comment)){
                $response["status"] = 1;
                $em = $this->getDoctrine()->getManager();
                $em->remove($comment);
                $em->flush();
            }
        }
        return $this->json($response);
    }
}
