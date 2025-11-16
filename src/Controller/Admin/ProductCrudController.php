<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setDefaultSort(['updatedAt' => 'DESC'])
            ->setPageTitle('index', 'Catalogue des vins')
            ->setPageTitle('new', 'Ajouter un nouveau vin')
            ->setPageTitle('edit', fn (Product $product) => sprintf('Modifier <b>%s</b>', $product->getName()));
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom du produit')
            ->setRequired(true)
            ->setHelp('Ex: Cuvée Prestige 2022');

        yield SlugField::new('slug', 'Slug')
            ->setTargetFieldName('name')
            ->setRequired(true)
            ->hideOnIndex();

        yield AssociationField::new('category', 'Catégorie')
            ->setRequired(true);

        yield TextareaField::new('description', 'Description')
            ->setRequired(true)
            ->hideOnIndex();

        yield TextField::new('grapeVariety', 'Cépage')
            ->setRequired(true)
            ->setHelp('Ex: Syrah, Grenache')
            ->hideOnIndex();

        yield MoneyField::new('price', 'Prix')
            ->setCurrency('EUR')
            ->setStoredAsCents(true)
            ->setRequired(true);

        yield TextField::new('imageUrl', 'URL de l\'image')
            ->setHelp('Chemin vers la photo du produit (ex: /images/products/vin-rouge.jpg)')
            ->hideOnIndex();

        yield BooleanField::new('isFeatured', 'Produit phare')
            ->setHelp('Afficher ce produit sur la page d\'accueil')
            ->renderAsSwitch(true);

        // Afficher l'image sous forme de miniature dans la liste
        if ($pageName === Crud::PAGE_INDEX) {
            yield ImageField::new('imageUrl', 'Photo')
                ->setBasePath('/')
                ->setUploadDir('public/');
        }

        yield DateTimeField::new('updatedAt', 'Dernière MàJ')
            ->onlyOnIndex();

        yield DateTimeField::new('createdAt', 'Date de création')
            ->hideOnIndex()
            ->hideOnForm();
    }
}
