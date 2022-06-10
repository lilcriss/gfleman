<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        
    }

    #[Route('/inscription', name: 'register')]
    public function index(UserRepository $userRepository, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $search_email = $userRepository->findOneBy(['email' => $user->getEmail()]);
            
            if (!$search_email) 
            {
                $password = $encoder->encodePassword($user, $user->getPassword());

                $user->setPassword($password);
                
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $mail = new Mail();
                $content = "Bonjour ".$user->getFirstName()." Votre compte à bien été créé";

                $mail->send(
                    $user->getEmail(), 
                    $user->getFirstName(),
                    "Bienvenue sur AfroBio", 
                    $content
                );

                $notification = "Votre inscription s'est correctement déroulée, vous pouvez maintenant vous connecter à votre compte";
            } else {
                $notification = "L'email que vous avez renseigné existe déjà";
            }

        }

        return $this->render('register/index.html.twig', [
            'form'=> $form->createView(),
            'notification' => $notification
        ]);
    }
}
