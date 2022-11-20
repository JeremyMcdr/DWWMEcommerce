<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    #[Route('/account/order', name: 'app_account_order')]
    public function index(): Response
    {
        return $this->render('account_order/index.html.twig', [
            'controller_name' => 'AccountOrderController',
        ]);
    }
}
