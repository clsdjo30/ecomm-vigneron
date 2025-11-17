<?php

namespace App\Controller\Admin;

use App\Entity\NewsletterSubscriber;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NewsletterSubscriberCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NewsletterSubscriber::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Abonné Newsletter')
            ->setEntityLabelInPlural('Abonnés Newsletter')
            ->setPageTitle('index', 'Liste des Abonnés Newsletter')
            ->setPageTitle('detail', fn (NewsletterSubscriber $subscriber) => $subscriber->getEmail())
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(50)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // Lecture seule : désactiver les actions de modification
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield EmailField::new('email', 'Email')
            ->setHelp('Adresse email de l\'abonné');

        yield DateTimeField::new('createdAt', 'Date d\'inscription')
            ->setFormat('dd/MM/yyyy HH:mm')
            ->hideOnForm();

        yield BooleanField::new('isConfirmed', 'Confirmé')
            ->renderAsSwitch(false);

        yield TextField::new('confirmationToken', 'Token de confirmation')
            ->hideOnIndex()
            ->setHelp('Token utilisé pour confirmer l\'inscription');
    }
}
