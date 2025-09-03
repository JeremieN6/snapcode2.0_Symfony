<?php

namespace App\Controller\Admin;

use App\Entity\QrScan;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class QrScanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return QrScan::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Scan')
            ->setEntityLabelInPlural('Scans')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('enseigne'),
            DateTimeField::new('createdAt','Date'),
            TextField::new('deviceType','Device'),
            TextField::new('ipAddress','IP')->onlyOnIndex(),
            TextField::new('country')->onlyOnIndex(),
            TextField::new('city')->onlyOnIndex(),
            TextField::new('userAgent')->hideOnIndex()
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add(EntityFilter::new('enseigne'));
    }
}
