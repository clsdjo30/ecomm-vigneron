<?php

namespace App\Controller;

use App\Entity\NewsletterSubscriber;
use App\Form\NewsletterType;
use App\Repository\PostRepository;
use App\Repository\ProductRepository;
use App\Repository\TeamMemberRepository;
use App\Repository\TestimonialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ProductRepository $productRepository,
        TestimonialRepository $testimonialRepository,
        TeamMemberRepository $teamMemberRepository,
        PostRepository $postRepository
    ): Response
    {
        // Créer le formulaire newsletter
        $newsletterForm = $this->createForm(NewsletterType::class, new NewsletterSubscriber());

        // Charger les produits phares (isFeatured = true)
        $featuredProducts = $productRepository->findBy(['isFeatured' => true], ['createdAt' => 'DESC'], 6);

        // Charger les témoignages publiés
        $testimonials = $testimonialRepository->findPublished(5);

        // Charger les membres de l'équipe actifs
        $teamMembers = $teamMemberRepository->findActive(4);

        // Charger les 3 derniers articles de blog publiés
        $recentPosts = $postRepository->findPublished(3);

        return $this->render('home/index.html.twig', [
            'newsletterForm' => $newsletterForm->createView(),
            'featuredProducts' => $featuredProducts,
            'testimonials' => $testimonials,
            'teamMembers' => $teamMembers,
            'recentPosts' => $recentPosts,
        ]);
    }
}
