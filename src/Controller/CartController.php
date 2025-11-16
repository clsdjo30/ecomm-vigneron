<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart_index')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);
        $cartWithData = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            if ($product) {
                $cartWithData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->getPrice() * $quantity
                ];
                $total += $product->getPrice() * $quantity;
            }
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cartWithData,
            'total' => $total
        ]);
    }

    #[Route('/panier/ajouter', name: 'app_cart_add', methods: ['POST'])]
    public function add(Request $request, SessionInterface $session, ProductRepository $productRepository): Response
    {
        $productId = $request->request->get('productId');
        $quantity = $request->request->getInt('quantity', 1);

        // Vérifier le token CSRF
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('add-to-cart', $token)) {
            $this->addFlash('danger', 'Token CSRF invalide');
            return $this->redirectToRoute('app_home');
        }

        // Vérifier que le produit existe
        $product = $productRepository->find($productId);
        if (!$product) {
            $this->addFlash('danger', 'Produit introuvable');
            return $this->redirectToRoute('app_home');
        }

        // Récupérer le panier depuis la session
        $cart = $session->get('cart', []);

        // Ajouter ou mettre à jour la quantité
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        // Sauvegarder le panier dans la session
        $session->set('cart', $cart);

        $this->addFlash('success', 'Produit ajouté au panier avec succès !');

        // Rediriger vers la page d'où vient l'utilisateur
        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/panier/modifier/{id}', name: 'app_cart_update', methods: ['POST'])]
    public function update(int $id, Request $request, SessionInterface $session): Response
    {
        $quantity = $request->request->getInt('quantity', 1);

        $cart = $session->get('cart', []);

        if ($quantity > 0) {
            $cart[$id] = $quantity;
            $this->addFlash('success', 'Quantité mise à jour');
        } else {
            unset($cart[$id]);
            $this->addFlash('success', 'Produit retiré du panier');
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/panier/supprimer/{id}', name: 'app_cart_remove')]
    public function remove(int $id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $this->addFlash('success', 'Produit retiré du panier');
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/panier/vider', name: 'app_cart_clear')]
    public function clear(SessionInterface $session): Response
    {
        $session->set('cart', []);
        $this->addFlash('success', 'Panier vidé');

        return $this->redirectToRoute('app_cart_index');
    }
}
