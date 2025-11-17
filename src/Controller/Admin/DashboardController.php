<?php

namespace App\Controller\Admin;

use App\Entity\BlogCategory;
use App\Entity\Category;
use App\Entity\ContactMessage;
use App\Entity\NewsletterSubscriber;
use App\Entity\Order;
use App\Entity\Post;
use App\Entity\Product;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\ContactMessageRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator,
        private ContactMessageRepository $contactMessageRepository
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('E-Commerce Vigneron - Administration')
            ->setFaviconPath('favicon.ico')
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::section('Catalogue');
        yield MenuItem::linkToCrud('Produits', 'fa fa-wine-bottle', Product::class);
        yield MenuItem::linkToCrud('Catégories', 'fa fa-tags', Category::class);

        yield MenuItem::section('Commandes');
        yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-cart', Order::class);

        yield MenuItem::section('Blog');
        yield MenuItem::linkToCrud('Articles', 'fa fa-newspaper', Post::class);
        yield MenuItem::linkToCrud('Catégories Blog', 'fa fa-folder', BlogCategory::class);
        yield MenuItem::linkToCrud('Tags', 'fa fa-tag', Tag::class);

        yield MenuItem::section('Communication');
        yield MenuItem::linkToCrud('Messages de Contact', 'fa fa-envelope', ContactMessage::class)
            ->setBadge(
                $this->contactMessageRepository->count(['isRead' => false]),
                'danger'
            );
        yield MenuItem::linkToCrud('Abonnés Newsletter', 'fa fa-newspaper', NewsletterSubscriber::class);

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class);

        yield MenuItem::section('');
        yield MenuItem::linkToUrl('Retour au site', 'fa fa-arrow-left', '/');
    }
}
