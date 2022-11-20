<?php

namespace App\Controller;


use App\Classe\Mail;
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
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        //dd($form);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();

            //vérifier si l'utilisateur n'existe pas en base de donnée
            $seartch_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if (!$seartch_email)
            {

                $password = $hasher->hashPassword($user,$user->getPassword());
                $user->setPassword($password);


                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $notification = "Votre inscription s'est correctement passée. Vous pouvez dès à présent vous connecter à votre compte";

                $content = 'Hey '.$user->getFirstName().'<br> Bienvenue sur la boutique du made in france !! <br> Merci beaucoup pour ton inscription sur notre site.';
                $email = new Mail();
                $email->send($user->getEmail(),$user->getFirstName(),'Bienvenue sur la boutique Francaise', $content);
            }else
            {
                $notification = "L'email que vous avez renseigné existe déjà";
            }


            //return $this->redirectToRoute('app_login');

            //Envoye du mail de confirmation

        }

        return $this->render('register/index.html.twig',[
            'form' => $form->createView(),
            'notification'=>$notification

        ]);
    }
}
