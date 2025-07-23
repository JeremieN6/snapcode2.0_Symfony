<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class CategoriesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categories::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories')
            ->setSearchFields(['name', 'description', 'slug'])
            ->setDefaultSort(['name' => 'ASC'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            
            TextField::new('name', 'Nom')
                ->setRequired(true)
                ->setColumns(6),
                
            TextField::new('slug', 'Slug')
                ->setHelp('URL-friendly version du nom (généré automatiquement si vide)')
                ->setColumns(6),
                
            TextareaField::new('description', 'Description')
                ->setHelp('Description de la catégorie pour le SEO')
                ->setRequired(false)
                ->hideOnIndex(),
                
            AssociationField::new('parent', 'Catégorie parente')
                ->setRequired(false)
                ->autocomplete()
                ->setHelp('Laissez vide pour une catégorie principale'),
                
            AssociationField::new('categories', 'Sous-catégories')
                ->hideOnForm()
                ->setTemplatePath('admin/field/subcategories.html.twig'),
                
            AssociationField::new('posts', 'Articles')
                ->hideOnForm()
                ->setTemplatePath('admin/field/category_posts_count.html.twig'),
                
            DateTimeField::new('createdAt', 'Date de création')
                ->hideOnForm()
                ->setFormat('dd/MM/yyyy HH:mm'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('parent', 'Catégorie parente'));
    }
}
