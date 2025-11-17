<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification anti-spam honeypot
            if (!empty($contactMessage->getWebsite())) {
                $this->addFlash('danger', 'Votre message n\'a pas pu être envoyé.');
                return $this->redirectToRoute('contact_index');
            }

            try {
                // Sauvegarder le message en base de données
                $entityManager->persist($contactMessage);
                $entityManager->flush();

                // Envoyer un email de notification à l'administrateur
                $email = (new TemplatedEmail())
                    ->from(new Address($contactMessage->getEmail(), $contactMessage->getName()))
                    ->to(new Address($this->getParameter('app.admin_email') ?? 'admin@example.com'))
                    ->subject('Nouveau message de contact: ' . $contactMessage->getSubject())
                    ->htmlTemplate('emails/contact_notification.html.twig')
                    ->context([
                        'contactMessage' => $contactMessage,
                    ]);

                $mailer->send($email);

                $this->addFlash('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');

                return $this->redirectToRoute('contact_index');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.');
            }
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
