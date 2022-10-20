<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart, Request $request): Response
    {
        if(!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('app_account_address_add');
        }

        $form = $this->createForm(OrderType::class, null,
        [
            'user'=>$this->getUser()
        ]);

        return $this->render('order/index.html.twig',
        [
            'form'=>$form->createView(),
            'cart'=>$cart->getFull(),
        ]);
    }


    #[Route('/commande/recapitulatif', name: 'app_order_recap')]
    public function add(Cart $cart, Request $request): Response
    {

        $form = $this->createForm(OrderType::class, null,
            [
                'user'=>$this->getUser()
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $date = new \DateTime();
            $carriers = $form->get('carriers');
            dd($carriers);

            //enregistrer ma commande : Order()
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());


            //enregistrer mes produits : OrderDetails
        }
        return $this->render('order/add.html.twig',
            [
                'cart'=>$cart->getFull(),
            ]);
    }
}
