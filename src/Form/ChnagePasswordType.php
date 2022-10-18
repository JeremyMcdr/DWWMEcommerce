<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class ChnagePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled'=>true,
                'label'=>'Mon Adresse Email'
            ])

            ->add('firstname', TextType::class ,
            [
                'disabled'=>true
            ])
            ->add('lastname', TextType::class,
            [
                'disabled'=>true
            ])

            ->add('old_password', PasswordType::class,[
                'mapped'=> false
            ])
            ->add('new_password', RepeatedType::class,[
                'type'=> PasswordType::class,
                'mapped'=> false,
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
                'label'=> "Valider les modifications"
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
