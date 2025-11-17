<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Articles')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPageTitle('index', 'Gestion des articles du blog')
            ->setPageTitle('new', 'Créer un article')
            ->setPageTitle('edit', 'Modifier l\'article');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('isPublished')
            ->add('category')
            ->add('tags')
            ->add('createdAt');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('title', 'Titre de l\'article')
            ->setColumns(8);

        yield SlugField::new('slug', 'URL')
            ->setTargetFieldName('title')
            ->setColumns(4)
            ->setHelp('Générée automatiquement à partir du titre');

        yield AssociationField::new('category', 'Catégorie')
            ->setRequired(true);

        yield AssociationField::new('tags', 'Tags')
            ->setHelp('Sélectionnez un ou plusieurs tags');

        $contentField = TextEditorField::new('content', 'Contenu')
            ->setFormType(CKEditorType::class)
            ->setFormTypeOptions([
                'config_name' => 'default',
            ])
            ->hideOnIndex();
        yield $contentField;

        yield TextareaField::new('excerpt', 'Extrait')
            ->setHelp('Court résumé affiché sur la page de liste')
            ->hideOnIndex()
            ->setMaxLength(500);

        $imageField = ImageField::new('featuredImage', 'Image à la une')
            ->setBasePath('/uploads/blog')
            ->setUploadDir('public/uploads/blog')
            ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
            ->setRequired(false);
        yield $imageField;

        yield BooleanField::new('isPublished', 'Publié')
            ->renderAsSwitch(true)
            ->setHelp('Rendre l\'article visible sur le site');

        yield DateTimeField::new('createdAt', 'Date de création')
            ->hideOnForm();

        yield DateTimeField::new('updatedAt', 'Dernière modification')
            ->hideOnForm();
    }
}
