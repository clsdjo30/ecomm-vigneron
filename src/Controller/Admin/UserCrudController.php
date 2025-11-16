<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Administrateur')
            ->setEntityLabelInPlural('Administrateurs')
            ->setPageTitle('index', 'Gestion des administrateurs')
            ->setPageTitle('new', 'Créer un nouvel administrateur')
            ->setPageTitle('edit', fn (User $user) => sprintf('Modifier <b>%s</b>', $user->getEmail()));
    }

    public function configureFields(string $pageName): iterable
    {
        yield EmailField::new('email', 'Email')
            ->setRequired(true);

        yield ChoiceField::new('roles', 'Rôles')
            ->setChoices([
                'Administrateur' => 'ROLE_ADMIN',
                'Utilisateur' => 'ROLE_USER',
            ])
            ->allowMultipleChoices()
            ->renderExpanded()
            ->formatValue(function ($value) {
                if (is_array($value)) {
                    $badges = [];
                    foreach ($value as $role) {
                        $badges[] = match($role) {
                            'ROLE_ADMIN' => '<span class="badge badge-danger">Administrateur</span>',
                            'ROLE_USER' => '<span class="badge badge-secondary">Utilisateur</span>',
                            default => '<span class="badge badge-info">' . htmlspecialchars($role) . '</span>'
                        };
                    }
                    return implode(' ', $badges);
                }
                return '';
            });

        $passwordField = TextField::new('password', 'Mot de passe')
            ->setFormType(\Symfony\Component\Form\Extension\Core\Type\PasswordType::class)
            ->onlyOnForms();

        if ($pageName === Crud::PAGE_NEW) {
            yield $passwordField->setRequired(true);
        } else {
            yield $passwordField
                ->setRequired(false)
                ->setHelp('Laissez vide pour ne pas modifier le mot de passe');
        }
    }
}
