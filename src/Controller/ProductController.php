<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category_show')]
    public function showCategory(Category $category): Response
    {
        return $this->render('product/category.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/produit/{slug}', name: 'app_product_detail')]
    public function showDetail(Product $product): Response
    {
        return $this->render('product/detail.html.twig', [
            'product' => $product,
        ]);
    }
}
