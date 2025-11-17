<?php

namespace App\Controller\Admin;

use App\Entity\Testimonial;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TestimonialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Testimonial::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Témoignage')
            ->setEntityLabelInPlural('Témoignages')
            ->setPageTitle('index', 'Liste des Témoignages')
            ->setDefaultSort(['displayOrder' => 'ASC', 'createdAt' => 'DESC'])
            ->setPaginatorPageSize(30)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('name', 'Nom')
            ->setColumns(6);

        yield TextField::new('position', 'Poste/Fonction')
            ->setColumns(6)
            ->setHelp('Ex: Client fidèle, Sommelier, etc.');

        yield TextareaField::new('content', 'Témoignage')
            ->setHelp('Maximum 1000 caractères');

        yield IntegerField::new('rating', 'Note')
            ->setHelp('De 1 à 5 étoiles')
            ->setColumns(4);

        yield ImageField::new('image', 'Photo')
            ->setBasePath('uploads/testimonials')
            ->setUploadDir('public/uploads/testimonials')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setColumns(4)
            ->setHelp('Photo du client (optionnel)');

        yield IntegerField::new('displayOrder', 'Ordre d\'affichage')
            ->setColumns(4)
            ->setHelp('Plus le nombre est petit, plus le témoignage apparaît en premier');

        yield BooleanField::new('isPublished', 'Publié')
            ->setHelp('Le témoignage est visible sur le site');

        yield DateTimeField::new('createdAt', 'Date de création')
            ->setFormat('dd/MM/yyyy HH:mm')
            ->hideOnForm();
    }
}
