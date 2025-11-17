<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\BlogCategory;
use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Page d'accueil avec message de bienvenue
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Domaine de la Gardiole');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');

        yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-cart', Order::class);

        yield MenuItem::section('Catalogue');
        yield MenuItem::linkToCrud('Produits', 'fa fa-wine-bottle', Product::class);
        yield MenuItem::linkToCrud('Catégories', 'fa fa-tags', Category::class);

        yield MenuItem::section('Blog');
        yield MenuItem::linkToCrud('Articles', 'fa fa-newspaper', Post::class);
        yield MenuItem::linkToCrud('Catégories Blog', 'fa fa-folder', BlogCategory::class);
        yield MenuItem::linkToCrud('Tags', 'fa fa-tag', Tag::class);

        yield MenuItem::section('Administration');
        yield MenuItem::linkToCrud('Administrateurs', 'fa fa-user', User::class);

        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out-alt');
    }
}
