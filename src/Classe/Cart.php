<?php

namespace App\Classe;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

Class Cart
{
    private $session;
    public function __construct(EntityManagerInterface $entityManager,RequestStack $stack)
    {
        $this->session = $stack->getSession();
        $this->entityManager = $entityManager;
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
        $cart = $this->session->get('cart', []);
        //vérifier si la quantité de notre produit = 1;

        if($cart[$id]>1)
        {
            //retirer une quantité
            $cart[$id]--;
        }else{
            //supprimer mon produits
            unset($cart[$id]);
        }
        return $this->session->set('cart', $cart);

    }

    public function getFull()
    {
        $cartComplete = [];

        if($this->get())
        {
            foreach ($this->get() as $id=>$quantity)
            {
                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);
                if(!$product_object)
                {
                    $this->delete($id);
                    continue;
                }
                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity'=> $quantity,
                ];
            }
        }
        return $cartComplete;
    }

}