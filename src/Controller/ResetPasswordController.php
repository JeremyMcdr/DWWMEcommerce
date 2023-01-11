<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{

    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/reset/password', name: 'app_reset_password')]
    public function index(Request $request): Response
    {


        if($this->getUser())
        {
            return $this->redirectToRoute('app_home');
        }

        //Récupération de notre email sur le formulaire
        if($request->get('email'))
        {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if($user)
            {
                //Etape 1, enregistrer en base la demande de reset_password avec user,token et createdAt
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                //Etape 2, envoyer un email avec un lien pour mettre à jour le mot de passe
                $url = $this->generateUrl('app_update_password',[
                    'token' => $reset_password->getToken()]);

                $content = "Bonjour".$user->getFirstName()."<br> Vous avez demandé à réinitialiser votre mot de passe sur le site de The French Boutique<br><br>";

                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='https://macadre.fr".$url."'>mettre à jour le mot de passe</a>.";

                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFirstName().' '.$user->getLastName(),"Demande de réinitialisation de mot de passe",$content);

                $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un mail pour réinitialiser votre mot de passe');
            }else
            {
                $this->addFlash('notice', 'Cette adresse email est inconnue');
            }
        }

        return $this->render('reset_password/index.html.twig', [
            'controller_name' => 'ResetPasswordController',
        ]);
    }

    #[Route('/edit_password/{token}', name: 'app_update_password')]
    public function update(Request $request,$token, UserPasswordHasherInterface $hasher)
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password)
        {
            return $this->redirectToRoute('app_reset_password');
        }
        //Vérifier si le createdAt = nom - 3h
        $now = new \DateTimeImmutable();

        if($now > $reset_password->getCreatedAt()->modify('+ 3 hour'))
        {
            //modifier mon mot de passe
            $this->addFlash('notice', 'Votre demande de mot de passe à expirée. Merci de la renouveler');
            return $this->redirectToRoute('app_reset_password');
        }

        //rendre une vue avec mot de passe et confirmé votre mot de passe
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $new_password = $form->get('new_password')->getData();
            //dd($new_password);
            //Encodage des mots de passe
            $password = $hasher->hashPassword($reset_password->getUser(),$new_password);
            $reset_password->getUser()->setPassword($password);

            //Flush en base de donnée
            $this->entityManager->flush();
            //Redirection vers la page de connection
            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig',
        [
            'form'=>$form->createView()
        ]);
    }
}
