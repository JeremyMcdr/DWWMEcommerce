<?php

namespace App\Form;


use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GestionCommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('state',  TextType::class,
            [
                'label'=>'coucou',
                'required'=>true,
            ])

            ->add('submit', SubmitType::class,
                [
                    'label'=>'Valider ma commande',
                    'attr'=>[
                        'class'=>'btn btn-success btn-block'
                    ]
                ])
        ;

        }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'order'=> Order::class,
        ]);
    }
}
