<?php

namespace App\Controller\Admin;

use App\Entity\Comments;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

class CommentsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comments::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commentaire')
            ->setEntityLabelInPlural('Commentaires')
            ->setSearchFields(['content'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            
            AssociationField::new('posts', 'Article')
                ->setRequired(true)
                ->autocomplete(),
                
            AssociationField::new('users', 'Auteur')
                ->setRequired(true)
                ->autocomplete(),
                
            TextareaField::new('content', 'Contenu')
                ->setRequired(true)
                ->setMaxLength(1000),
                
            AssociationField::new('parent', 'Commentaire parent')
                ->setRequired(false)
                ->autocomplete()
                ->setHelp('Laissez vide pour un commentaire principal'),
                
            BooleanField::new('isReply', 'Est une réponse')
                ->hideOnForm(),
                
            BooleanField::new('isApproved', 'Approuvé')
                ->setHelp('Cochez pour approuver et publier le commentaire'),
                
            AssociationField::new('replies', 'Réponses')
                ->hideOnForm()
                ->setTemplatePath('admin/field/comment_replies_count.html.twig'),
                
            DateTimeField::new('createdAt', 'Date de création')
                ->hideOnForm()
                ->setFormat('dd/MM/yyyy HH:mm'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('posts', 'Article'))
            ->add(EntityFilter::new('users', 'Auteur'))
            ->add(BooleanFilter::new('isApproved', 'Approuvé'))
            ->add(BooleanFilter::new('isReply', 'Est une réponse'))
            ->add(DateTimeFilter::new('createdAt', 'Date de création'));
    }
}
