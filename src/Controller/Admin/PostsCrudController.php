<?php

namespace App\Controller\Admin;

use App\Entity\Posts;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class PostsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Posts::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Articles')
            ->setSearchFields(['title', 'content', 'slug'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::new('generateArticle', 'Générer un Article')
                ->linkToRoute('admin_article_generator')
                ->setIcon('fa fa-magic')
                ->setCssClass('btn btn-primary')
                ->displayAsButton());
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            
            TextField::new('title', 'Titre')
                ->setRequired(true)
                ->setColumns(6),
                
            TextField::new('slug', 'Slug')
                ->setHelp('URL-friendly version du titre (généré automatiquement si vide)')
                ->setColumns(6),
                
            TextareaField::new('metaTitle', 'Titre SEO')
                ->setHelp('Titre optimisé pour les moteurs de recherche (60 caractères max)')
                ->setColumns(6),
                
            TextareaField::new('metaDescription', 'Description SEO')
                ->setHelp('Description pour les moteurs de recherche (160 caractères max)')
                ->setColumns(6),
                
            TextField::new('featuredImage', 'Image à la une'),
                
            TextEditorField::new('content', 'Contenu')
                ->setRequired(true)
                ->hideOnIndex(),
                
            AssociationField::new('categories', 'Catégories')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                ])
                ->autocomplete(),
                
            AssociationField::new('keywords', 'Mots-clés')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                ])
                ->autocomplete()
                ->hideOnIndex(),
                
            AssociationField::new('users', 'Auteur')
                ->setRequired(true)
                ->autocomplete(),
                
            BooleanField::new('isPublished', 'Publié')
                ->setHelp('Cochez pour publier l\'article sur le site'),
                
            BooleanField::new('isFavorite', 'Article en vedette')
                ->setHelp('Cochez pour mettre en avant cet article'),
                
            DateTimeField::new('createdAt', 'Date de création')
                ->setFormat('dd/MM/yyyy HH:mm'),
                
            DateTimeField::new('updatedAt', 'Dernière modification')
                ->hideOnForm()
                ->setFormat('dd/MM/yyyy HH:mm'),

            BooleanField::new('isHeadline', 'Article en tête de page')
                ->setHelp('Cochez pour afficher l\'article en tête de page'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('categories', 'Catégories'))
            ->add(EntityFilter::new('users', 'Auteur'))
            ->add(BooleanFilter::new('isPublished', 'Publié'))
            ->add(BooleanFilter::new('isFavorite', 'En vedette'))
            ->add(DateTimeFilter::new('createdAt', 'Date de création'));
    }
}
