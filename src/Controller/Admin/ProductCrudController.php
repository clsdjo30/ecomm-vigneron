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

        $imageField = ImageField::new('imageUrl', 'Photo du produit')
            ->setBasePath('/uploads/products')
            ->setUploadDir('public/uploads/products')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->setRequired(false);

        if ($pageName === Crud::PAGE_INDEX) {
            $imageField->setHelp('');
        } else {
            $imageField->setHelp('Téléchargez une photo du produit (JPG, PNG)');
        }

        yield $imageField;

        yield BooleanField::new('isFeatured', 'Produit phare')
            ->setHelp('Afficher ce produit sur la page d\'accueil')
            ->renderAsSwitch(true);

        yield DateTimeField::new('updatedAt', 'Dernière MàJ')
            ->onlyOnIndex();

        yield DateTimeField::new('createdAt', 'Date de création')
            ->hideOnIndex()
            ->hideOnForm();
    }
}
