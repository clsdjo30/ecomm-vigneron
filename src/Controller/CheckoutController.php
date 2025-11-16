<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CheckoutController extends AbstractController
{
    #[Route('/commande', name: 'app_checkout_index')]
    public function index(
        Request $request,
        SessionInterface $session,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifier que le panier n'est pas vide
        $cart = $session->get('cart', []);
        if (empty($cart)) {
            $this->addFlash('warning', 'Votre panier est vide');
            return $this->redirectToRoute('app_cart_index');
        }

        // Préparer les données du panier pour l'affichage
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

        // Créer le formulaire de commande
        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Générer une référence unique pour la commande
            $reference = 'CMD-' . date('Y') . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);

            // Créer la commande
            $order = new Order();
            $order->setReference($reference);
            $order->setCustomerName($data['customerName']);
            $order->setCustomerEmail($data['customerEmail']);
            $order->setTotalAmount($total);
            $order->setStatus(Order::STATUS_PENDING);

            // Créer les détails de la commande
            foreach ($cartWithData as $item) {
                $orderDetail = new OrderDetail();
                $orderDetail->setRelatedOrder($order);
                $orderDetail->setProduct($item['product']);
                $orderDetail->setProductName($item['product']->getName());
                $orderDetail->setQuantity($item['quantity']);
                $orderDetail->setPricePerUnit($item['product']->getPrice());
                $orderDetail->calculateTotalPrice();

                $order->addOrderDetail($orderDetail);
                $entityManager->persist($orderDetail);
            }

            // Sauvegarder la commande
            $entityManager->persist($order);
            $entityManager->flush();

            // Vider le panier
            $session->set('cart', []);

            // Sauvegarder la référence de commande en session pour la page de succès
            $session->set('last_order_reference', $reference);

            // Pour le moment, on simule le paiement réussi
            // Dans une vraie application, on redirigerait vers Stripe ici
            $order->setStatus(Order::STATUS_PAID);
            $entityManager->flush();

            // Rediriger vers la page de confirmation
            return $this->redirectToRoute('app_checkout_success');
        }

        return $this->render('checkout/index.html.twig', [
            'orderForm' => $form->createView(),
            'cart' => $cartWithData,
            'total' => $total
        ]);
    }

    #[Route('/commande/merci', name: 'app_checkout_success')]
    public function success(SessionInterface $session): Response
    {
        $reference = $session->get('last_order_reference');

        if (!$reference) {
            return $this->redirectToRoute('app_home');
        }

        // Nettoyer la session
        $session->remove('last_order_reference');

        return $this->render('checkout/success.html.twig', [
            'reference' => $reference
        ]);
    }
}
