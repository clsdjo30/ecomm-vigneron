<?php

namespace App\Controller\Admin;

use App\Entity\TeamMember;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class TeamMemberCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TeamMember::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Membre de l\'équipe')
            ->setEntityLabelInPlural('Équipe')
            ->setPageTitle('index', 'Membres de l\'équipe')
            ->setDefaultSort(['displayOrder' => 'ASC', 'name' => 'ASC'])
            ->setPaginatorPageSize(30)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('name', 'Nom')
            ->setColumns(6);

        yield TextField::new('role', 'Rôle')
            ->setColumns(6)
            ->setHelp('Ex: Vigneron, Sommelier, Responsable cave, etc.');

        yield TextareaField::new('bio', 'Biographie')
            ->setHelp('Courte description (max 500 caractères)')
            ->hideOnIndex();

        yield ImageField::new('photo', 'Photo')
            ->setBasePath('uploads/team')
            ->setUploadDir('public/uploads/team')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setHelp('Photo du membre de l\'équipe');

        yield EmailField::new('email', 'Email')
            ->setColumns(6)
            ->hideOnIndex();

        yield TelephoneField::new('phone', 'Téléphone')
            ->setColumns(6)
            ->hideOnIndex();

        yield UrlField::new('linkedinUrl', 'LinkedIn')
            ->hideOnIndex()
            ->setHelp('URL du profil LinkedIn (optionnel)');

        yield IntegerField::new('displayOrder', 'Ordre d\'affichage')
            ->setColumns(4)
            ->setHelp('Plus le nombre est petit, plus le membre apparaît en premier');

        yield BooleanField::new('isActive', 'Actif')
            ->setHelp('Le membre est visible sur le site');
    }
}
