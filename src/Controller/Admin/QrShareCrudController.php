<?php

namespace App\Controller\Admin;

use App\Entity\QrShare;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class QrShareCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return QrShare::class; }

    public function configureCrud(Crud $crud): Crud
    { return $crud->setEntityLabelInPlural('Partages')->setEntityLabelInSingular('Partage')->setDefaultSort(['id'=>'DESC']); }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('enseigne'),
            TextField::new('channel','Canal'),
            DateTimeField::new('createdAt','Date')
        ];
    }

    public function configureFilters(Filters $filters): Filters
    { return $filters->add(EntityFilter::new('enseigne')); }
}
