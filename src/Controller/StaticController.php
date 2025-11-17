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
use Symfony\Component\Routing\Attribute\Route;

class StaticController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer
    ) {
    }

    #[Route('/le-domaine', name: 'app_domain')]
    public function domain(): Response
    {
        return $this->render('static/domain.html.twig');
    }

    #[Route('/nous-contacter', name: 'app_contact')]
    public function contact(Request $request): Response
    {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification anti-spam honeypot
            if (!empty($contactMessage->getWebsite())) {
                $this->addFlash('danger', 'Votre message n\'a pas pu être envoyé.');
                return $this->redirectToRoute('app_contact');
            }

            try {
                // Sauvegarder le message en base de données
                $this->entityManager->persist($contactMessage);
                $this->entityManager->flush();

                // Envoyer un email de notification à l'administrateur
                try {
                    $email = (new TemplatedEmail())
                        ->from(new Address($contactMessage->getEmail(), $contactMessage->getName()))
                        ->to(new Address($this->getParameter('app.admin_email')))
                        ->subject('Nouveau message de contact: ' . $contactMessage->getSubject())
                        ->htmlTemplate('emails/contact_notification.html.twig')
                        ->context([
                            'contactMessage' => $contactMessage,
                        ]);

                    $this->mailer->send($email);
                } catch (\Exception $e) {
                    // L'email n'a pas pu être envoyé, mais le message est sauvegardé
                    // On log l'erreur mais on continue
                }

                $this->addFlash('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');

                return $this->redirectToRoute('app_contact');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.');
            }
        }

        return $this->render('static/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
