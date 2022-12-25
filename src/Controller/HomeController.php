<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\LogupType;
use App\Entity\User;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        if($this->getUser() == null)return $this->redirect("/connexion");
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/inscription", name="security_logup")
     */
    public function logup(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if($this->getUser() != null)return $this->redirect("/");

        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $form = $this->createForm(LogupType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            if($form->isValid()) {
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user
                    ->setPassword($password)
                    ->setCreated(new \DateTime());
                $em->persist($user);
                $em->flush();
                return $this->redirect("/connexion");
            }
        }
        return $this->render('home/logup.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser() != null)return $this->redirect("/");

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        dump($error);
        if (!empty($error)) {
            $this->addFlash(
                'error',
                'Erreurs'
            );
        }
        return $this->render('home/login.html.twig', [
            'last_username' => $lastUsername
        ]);
    }

    /**
     * @Route("/deconnexion",name="security_logout")
     */
    public function logout()
    {
        //Cette méthode peut rester vide, il faut juste que sa route existe
        // pour être passée dans la section logout de config/packages/security.yaml
    }
}
