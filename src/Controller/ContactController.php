<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
            ->from('contact@lesite.fr')
            ->to(['lilcriss@hotmail.fr', ''])
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('
                <h1>Titre du mail</h1>
                <p>'.$request->get('contact')['content'].'</p>
            ');

            $mailer->send($email);

            $this->addFlash("notice", "Merci de m'avoir contacté. Je vais vous répondre dans les meilleurs délais."); 
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
