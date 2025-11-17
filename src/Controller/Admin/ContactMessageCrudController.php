<?php

namespace App\Controller\Admin;

use App\Entity\ContactMessage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

class ContactMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactMessage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Message de Contact')
            ->setEntityLabelInPlural('Messages de Contact')
            ->setPageTitle('index', 'Liste des Messages de Contact')
            ->setPageTitle('detail', fn (ContactMessage $message) => sprintf('%s - %s', $message->getName(), $message->getSubject()))
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(30)
            ->showEntityActionsInlined()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        // Action personnalisée pour marquer comme lu
        $markAsRead = Action::new('markAsRead', 'Marquer comme lu', 'fa fa-check')
            ->linkToCrudAction('markAsRead')
            ->displayIf(fn (ContactMessage $message) => !$message->isRead())
            ->setCssClass('btn btn-sm btn-success');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $markAsRead)
            ->add(Crud::PAGE_DETAIL, $markAsRead)
            // Désactiver la création et modification (formulaire front seulement)
            ->disable(Action::NEW, Action::EDIT)
            // Permettre la suppression
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setCssClass('btn btn-sm btn-danger');
            })
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isRead', 'Statut'))
            ->add(ChoiceFilter::new('subject', 'Sujet')
                ->setChoices(ContactMessage::getSubjectChoices()))
            ->add(DateTimeFilter::new('createdAt', 'Date'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm();

        yield TextField::new('name', 'Nom')
            ->setColumns(6);

        yield EmailField::new('email', 'Email')
            ->setColumns(6);

        yield TelephoneField::new('phone', 'Téléphone')
            ->setColumns(6)
            ->hideOnIndex();

        yield ChoiceField::new('subject', 'Sujet')
            ->setChoices(ContactMessage::getSubjectChoices())
            ->setColumns(6)
            ->renderAsBadges([
                'Information générale' => 'primary',
                'Question sur un produit' => 'info',
                'Problème de commande' => 'warning',
                'Autre' => 'secondary',
            ]);

        yield DateTimeField::new('createdAt', 'Date')
            ->setFormat('dd/MM/yyyy HH:mm')
            ->hideOnForm();

        yield BooleanField::new('isRead', 'Lu')
            ->renderAsSwitch(false);

        yield TextareaField::new('message', 'Message')
            ->hideOnIndex()
            ->setHelp('Contenu du message envoyé par le visiteur');
    }

    public function markAsRead(AdminContext $context)
    {
        $message = $context->getEntity()->getInstance();

        if (!$message instanceof ContactMessage) {
            throw new \LogicException('Entity is missing or not a ContactMessage');
        }

        $message->setIsRead(true);
        $this->container->get('doctrine')->getManager()->flush();

        $this->addFlash('success', 'Le message a été marqué comme lu.');

        return $this->redirect($context->getReferrer());
    }
}
