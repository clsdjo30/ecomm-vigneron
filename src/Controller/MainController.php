<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        // Récupérer les 3 produits phares (isFeatured = true)
        $featuredProducts = $productRepository->findBy(['isFeatured' => true], null, 3);

        return $this->render('main/index.html.twig', [
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
