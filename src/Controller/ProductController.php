<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category_show')]
    public function showCategory(string $slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie introuvable');
        }

        return $this->render('product/category.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_product_detail')]
    public function showDetail(string $slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw $this->createNotFoundException('Produit introuvable');
        }

        return $this->render('product/detail.html.twig', [
            'product' => $product,
        ]);
    }
}
