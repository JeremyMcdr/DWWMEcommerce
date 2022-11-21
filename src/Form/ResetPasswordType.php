<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_password', RepeatedType::class,[
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
                'label'=> "Valider les modifications",
                'attr'=> [
                    'class'=>'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
