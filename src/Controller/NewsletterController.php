<?php

namespace App\Controller;

use App\Entity\NewsletterSubscriber;
use App\Form\NewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter/subscribe', name: 'newsletter_subscribe', methods: ['POST'])]
    public function subscribe(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subscriber = new NewsletterSubscriber();
        $form = $this->createForm(NewsletterType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($subscriber);
                $entityManager->flush();

                $this->addFlash('success', 'Merci pour votre inscription ! Veuillez consulter votre email pour confirmer votre abonnement.');

                // TODO: Envoyer un email de confirmation avec le lien contenant le token
                // $confirmationUrl = $this->generateUrl('newsletter_confirm', ['token' => $subscriber->getConfirmationToken()], UrlGeneratorInterface::ABSOLUTE_URL);

            } catch (\Exception $e) {
                // En cas d'erreur (email déjà inscrit par exemple)
                $this->addFlash('danger', 'Cette adresse email est déjà inscrite à notre newsletter.');
            }
        } else {
            $this->addFlash('danger', 'Veuillez fournir une adresse email valide.');
        }

        // Redirection vers la page précédente ou la page d'accueil
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: $this->generateUrl('app_home'));
    }

    #[Route('/newsletter/confirm/{token}', name: 'newsletter_confirm', methods: ['GET'])]
    public function confirm(string $token, EntityManagerInterface $entityManager): Response
    {
        $subscriber = $entityManager->getRepository(NewsletterSubscriber::class)->findOneBy(['confirmationToken' => $token]);

        if (!$subscriber) {
            $this->addFlash('danger', 'Token de confirmation invalide.');
            return $this->redirectToRoute('app_home');
        }

        if ($subscriber->isConfirmed()) {
            $this->addFlash('info', 'Votre email est déjà confirmé.');
            return $this->redirectToRoute('app_home');
        }

        $subscriber->confirm();
        $entityManager->flush();

        $this->addFlash('success', 'Votre inscription à la newsletter a été confirmée avec succès !');

        return $this->redirectToRoute('app_home');
    }
}
