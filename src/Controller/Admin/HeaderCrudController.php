<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
           TextField::new('title', 'Titre du header'),
            TextareaField::new('content', 'Contenue du header'),
            TextField::new('btnTitle', 'Titre de notre bouton'),
            TextField::new('btnUrl', 'url de notre bouton'),
            ImageField::new('illustration')
                ->setBasePath('uploads')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern("[name].[extension]")
                ->setRequired(false),

        ];
    }

}
