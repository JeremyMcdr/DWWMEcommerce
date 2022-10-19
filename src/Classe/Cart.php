<?php

namespace App\Classe;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

Class Cart
{
    private $session;
    public function __construct(RequestStack $stack)
    {
        $this->session = $stack->getSession();
    }

    public function add($id): void
    {
        $cart = $this->session->get('cart', []);
        if(!empty($cart[$id]))
        {
            $cart[$id]++;
        }else
        {
            $cart[$id]=1;
        }


       $this->session->set('cart', $cart);

    }
    public function get()
    {

        return $this->session->get('cart');
    }

    public function remove()
    {

        return $this->session->remove('cart');
    }

    public function  delete($id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
        //test
    }

    public function  decrease($id)
    {
        //vérifier si la quantité de notre produit = 1;
    }
}