<?php

namespace App\Controller\Admin;

use App\Entity\MoreInfoFormulaireController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MoreInfoFormulaireControllerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MoreInfoFormulaireController::class;
    }

    /**/
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom'),
            EmailField::new('email'),
            IntegerField::new('telephone', 'Téléphone'),
            TextField::new('website', 'Site web Entreprise'),
            TextField::new('serviceTypeSuperSite', "Besoin Super Site"),
            TextField::new('serviceTypeAutre', 'Autre besoin'),
            TextField::new('description', 'Besoin Client'),
        ];
    }
    
}
