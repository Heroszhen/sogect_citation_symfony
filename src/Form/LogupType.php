<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class LogupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'label' => "E-mail *",
                'attr' => ['placeholder' => "e-mail"],
                'constraints'=> [
                    new NotBlank(['message'=> 'Le mail est obligatoire']),
                    new Email(['message' => 'Indiquez un mail valide'])
                ],
                'empty_data' => '',
                'required' => false
            ])
            ->add('password',PasswordType::class,[
                'label' => "Mot de passe *",
                'attr' => ['placeholder' => "mot de passe"],
                'constraints' => [
                    new NotBlank(['message'=> 'Le mot de passe est obligatoire'])
                ],
                'empty_data' => '',
                'required' => false
            ])
            ->add('name', TextType::class,[
                'label' => "Nom *",
                'attr' => ['placeholder' => "nom"],
                'constraints' => [
                    new NotBlank(['message'=> 'Veuillez mettre un nom'])
                ],
                'empty_data' => '',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
