<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StaticController extends AbstractController
{
    #[Route('/le-domaine', name: 'app_domain')]
    public function domain(): Response
    {
        return $this->render('static/domain.html.twig');
    }

    #[Route('/nous-contacter', name: 'app_contact')]
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Ici, vous pouvez envoyer un email avec le service Mailer de Symfony
            // Pour l'instant, on simule juste l'envoi

            $this->addFlash('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('static/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
