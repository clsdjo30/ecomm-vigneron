<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories')
            ->setPageTitle('index', 'Catégories de vins')
            ->setPageTitle('new', 'Ajouter une nouvelle catégorie')
            ->setPageTitle('edit', fn (Category $category) => sprintf('Modifier <b>%s</b>', $category->getName()));
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom')
            ->setRequired(true)
            ->setHelp('Ex: Vins Rouges, Vins Blancs, Vins Rosés');

        yield SlugField::new('slug', 'Slug')
            ->setTargetFieldName('name')
            ->setRequired(true)
            ->setHelp('Généré automatiquement à partir du nom');
    }
}
