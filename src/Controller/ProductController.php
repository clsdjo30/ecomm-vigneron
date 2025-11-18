<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/nos-vins', name: 'app_products')]
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $categorySlug = $request->query->get('category');
        $sortBy = $request->query->get('sort', 'name'); // default: name

        // Get all categories for the filter
        $categories = $categoryRepository->findAll();

        // Filter by category if specified
        if ($categorySlug) {
            $category = $categoryRepository->findOneBy(['slug' => $categorySlug]);
            $products = $category ? $category->getProducts()->toArray() : [];
        } else {
            $products = $productRepository->findAll();
        }

        // Sort products
        usort($products, function($a, $b) use ($sortBy) {
            if ($sortBy === 'price_asc') {
                return $a->getPrice() <=> $b->getPrice();
            } elseif ($sortBy === 'price_desc') {
                return $b->getPrice() <=> $a->getPrice();
            } else { // name
                return strcmp($a->getName(), $b->getName());
            }
        });

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $categorySlug,
            'currentSort' => $sortBy,
        ]);
    }

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
