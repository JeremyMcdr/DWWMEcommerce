<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Order;
use App\Entity\User;
use App\Form\GestionCommandeType;
use Doctrine\ORM\EntityManagerInterface;


use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockGestionController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/stock/gestion', name: 'app_stock_gestion')]
    public function index(): Response
    {
        //Il faut afficher toutes les commandes
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        return $this->render('stock_gestion/index.html.twig',
        [
            'orders'=>$orders,
        ]);
    }

    #[Route('/stock/gestion/{reference}', name: 'app_stock_gestion_edit')]
    public function show($reference , Request $request, ManagerRegistry $managerRegistry): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);

        $form = $this->createForm(GestionCommandeType::class, $order);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        }
        //Récupération de l'id de l'utilisateur
        $userid = $order->getUser()->getId();

        $user = $this->entityManager->getRepository(User::class)->findOneById($userid);

        //Filtre du statut de la commande
        if ($order->getState() == 1)
        {
            $content = 'Hey '.$user->getFirstName().'<br> Votre commande est validée ! <br> Encore merci de nous faire confiance.';
        }elseif($order->getState() == 2)
        {
            $content = 'Hey '.$user->getFirstName()."<br> Votre commande est en train d'être préparée avec nos soins <br> Encore merci de nous faire confiance.";
            $email = new Mail();
            $email->send($user->getEmail(),$user->getFirstName(),'Votre commande est en cours de préparation', $content);
        }else
        {
            $content = 'Hey '.$user->getFirstName().'<br> Votre commande vient de quitter nos locaux ! <br> Encore merci de nous faire confiance.';
            $email = new Mail();
            $email->send($user->getEmail(),$user->getFirstName(),'Votre commande est en livraison', $content);

        }






        return $this->render('stock_gestion/stock_show.html.twig', [
            'order' => $order,
            'form'=>$form->createView()
        ]);
    }

}
