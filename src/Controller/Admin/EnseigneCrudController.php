<?php

namespace App\Controller\Admin;

use App\Entity\Enseigne;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EnseigneCrudController extends AbstractCrudController
{
    public function __construct(private ParameterBagInterface $params) {}

    public static function getEntityFqcn(): string
    {
        return Enseigne::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Enseigne')
            ->setEntityLabelInPlural('Enseignes')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $download = Action::new('downloadQr', 'Télécharger QR')
            ->linkToRoute('admin_enseigne_download_qr', function(Enseigne $e){ return ['id' => $e->getId()]; })
            ->setIcon('fa fa-download')
            ->displayIf(static fn(Enseigne $e) => $e->getQrFilename());

        return $actions
            ->add(Crud::PAGE_INDEX, $download);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom')->setRequired(true),
            TextField::new('uuid')->onlyOnDetail(),
            TextField::new('trackingUrl')->hideOnForm(),
            ImageField::new('qrFilename', 'QR Code')
                ->hideOnForm()
                ->setBasePath('/'),
            IntegerField::new('totalScans', 'Scans')
                ->onlyOnIndex()
        ];
    }

    #[Route('/admin/enseigne/{id}/qr', name: 'admin_enseigne_download_qr')]
    public function downloadQr(Enseigne $enseigne): BinaryFileResponse
    {
        $path = $this->params->get('kernel.project_dir') . '/public/' . $enseigne->getQrFilename();
        return $this->file($path, 'qr-'.$enseigne->getUuid().'.png', ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }
}
