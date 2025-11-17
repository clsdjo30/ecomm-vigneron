<?php

namespace App\Controller;

use App\Form\NewsletterType;
use App\Entity\NewsletterSubscriber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Créer le formulaire newsletter pour l'afficher sur la page d'accueil
        $newsletterForm = $this->createForm(NewsletterType::class, new NewsletterSubscriber());

        return $this->render('home/index.html.twig', [
            'newsletter_form' => $newsletterForm->createView(),
        ]);
    }
}
