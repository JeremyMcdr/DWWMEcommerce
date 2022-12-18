<?php

namespace App\Controller;

use App\Entity\Order;
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



        return $this->render('stock_gestion/stock_show.html.twig', [
            'order' => $order,
            'form'=>$form->createView()
        ]);
    }

}
