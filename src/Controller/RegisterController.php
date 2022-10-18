<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends AbstractController
{   
    
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    


    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        //dd($form);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();

            $password = $hasher->hashPassword($user,$user->getPassword());
            $user->setPassword($password);


            $this->entityManager->persist($user);
            $this->entityManager->flush();
           
            //$doctrine = $this->getDoctrine()->getManager();
            
            //$doctrine->persist($user);
            //$doctrine->flush();
        }

        return $this->render('register/index.html.twig',[
        'form' => $form->createView()]);
    }
}
