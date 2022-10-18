<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[ 'label'=>'Votre Prenom','constraints'=>new Length(['min' => 2, 'max'=>30]), 'attr'=>[
                'placeholder' => 'Merci de saisir votre prénom']
            ])
            ->add('lastname', TextType::class,[ 'label'=>'Votre Nom', 'attr'=>[
                'placeholder' => 'Merci de saisir votre Nom']
            ])
            
            ->add('email', EmailType::class,[ 'label'=>'Votre Email', 'attr'=>[
                'placeholder' => 'Merci de saisir votre email']
            ])
            
            ->add('password', RepeatedType::class,[
                'type'=> PasswordType::class,
                'invalid_message'=>'Le mot de passe et la confirmation doivent etre le meme',
                'label'=>true,
                'first_options'=>[
                    'label'=>'Mot De Passe',
                    'attr'=>[
                        'placeholder'=>'Merci de saisir votre mdp'
                    ]
                ],
            'second_options'=>[
                'label'=>'Confirmez votre mot de passe',
                'attr'=>[
                    'placeholder'=>'Mot de passe'
            ]
            ]
    ])

            ->add('submit', SubmitType::class,[
                'label'=> "S'inscrire"
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
