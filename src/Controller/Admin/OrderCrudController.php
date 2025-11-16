<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPageTitle('index', 'Liste des commandes')
            ->setPageTitle('detail', fn (Order $order) => sprintf('Commande #%s', $order->getReference()));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // Désactiver les actions de modification
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            // Garder uniquement index et detail
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('status')
            ->add(DateTimeFilter::new('createdAt', 'Date de commande'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('reference', 'Référence');

        yield TextField::new('status', 'Statut')
            ->formatValue(function ($value, Order $order) {
                $badges = [
                    Order::STATUS_PENDING => '<span class="badge badge-warning">En attente</span>',
                    Order::STATUS_PAID => '<span class="badge badge-success">Payée</span>',
                    Order::STATUS_CANCELLED => '<span class="badge badge-danger">Annulée</span>',
                ];
                return $badges[$value] ?? $value;
            });

        yield MoneyField::new('totalAmount', 'Montant Total')
            ->setCurrency('EUR')
            ->setStoredAsCents(true);

        yield TextField::new('customerName', 'Nom du Client');
        yield EmailField::new('customerEmail', 'Email du Client');
        yield DateTimeField::new('createdAt', 'Date de commande');

        // Pour la page de détail, afficher les détails de commande
        if ($pageName === Crud::PAGE_DETAIL) {
            yield ArrayField::new('orderDetails', 'Détails de la commande')
                ->formatValue(function ($value, Order $order) {
                    $html = '<table class="table table-striped">';
                    $html .= '<thead><tr>';
                    $html .= '<th>Produit</th>';
                    $html .= '<th>Quantité</th>';
                    $html .= '<th>Prix unitaire</th>';
                    $html .= '<th>Total</th>';
                    $html .= '</tr></thead><tbody>';

                    foreach ($order->getOrderDetails() as $detail) {
                        $html .= '<tr>';
                        $html .= '<td>' . htmlspecialchars($detail->getProductName()) . '</td>';
                        $html .= '<td>' . $detail->getQuantity() . '</td>';
                        $html .= '<td>' . number_format($detail->getPricePerUnitInEuros(), 2) . ' €</td>';
                        $html .= '<td>' . number_format($detail->getTotalPriceInEuros(), 2) . ' €</td>';
                        $html .= '</tr>';
                    }

                    $html .= '</tbody></table>';
                    return $html;
                });

            yield TextField::new('stripeSessionId', 'ID Session Stripe')
                ->hideOnIndex();
        }
    }
}
