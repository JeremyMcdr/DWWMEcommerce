<?php

namespace App\Controller;

use App\Classe\Cart;
//use App\Classe\Mail;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="app_commande_merci")
     */
    public function index(Cart $cart, $stripeSessionId)
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

                if (!$order || $order->getUser() != $this->getUser()) {

                    return $this->redirectToRoute('home');

                }


                if ($order->isIsPaid() == 0) {
                    // Vider la session "cart"
                    $cart->remove();

                    // Modifier le statut isPaid de notre commande en mettant 1
                    $order->setIsPaid(1);
                    $this->entityManager->flush();

                    // Envoyer un email à notre client pour lui confirmer sa commande
                    $content = 'Hey '.$order->getUser()->getFirstName().'<br> Ta commande est validée voici un debrief : <br> Produits acheté(s) : ' . $order->getDelivery() ;
                    //$email = new Mail();
                    //$email->send($order->getUser()->getEmail(),$order->getUser()->getFirstName(),'Votre commande La Boutique Francaise est validée !', $content);


                }

        dd(($order->getTotal())."€");
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
