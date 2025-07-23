<?php

namespace App\Controller\Admin;

use App\Entity\Keywords;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class KeywordsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Keywords::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Mot-clé')
            ->setEntityLabelInPlural('Mots-clés')
            ->setSearchFields(['name', 'slug'])
            ->setDefaultSort(['name' => 'ASC'])
            ->setPaginatorPageSize(50);
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
                
            AssociationField::new('posts', 'Articles associés')
                ->hideOnForm()
                ->setTemplatePath('admin/field/keyword_posts_count.html.twig'),
                
            DateTimeField::new('createdAt', 'Date de création')
                ->hideOnForm()
                ->setFormat('dd/MM/yyyy HH:mm'),
        ];
    }
}
