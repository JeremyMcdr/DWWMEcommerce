<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->addFlash('notice', 'Merci pour votre contact');

            dd($form->getData());
            //Faire un truc qui envoie un mail à un administrateur, ou à une personne dédié à la réponse de formulaire
            //Création d'une adresse mail administrateur
            //Puis envoie de l'adresse mail sur ce dernier
            //$mail = new Mail();
            //$mail->send();
        }

        return $this->render('contact/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
