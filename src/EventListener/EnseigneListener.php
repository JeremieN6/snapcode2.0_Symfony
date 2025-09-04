<?php

namespace App\EventListener;

use App\Entity\Enseigne;
use App\Service\QrCodeGenerator;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Enseigne::class)]
class EnseigneListener
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private QrCodeGenerator $qrCodeGenerator,
    ) {}

    public function prePersist(Enseigne $enseigne, PrePersistEventArgs $args): void
    {
        if ($enseigne->getTrackingUrl() ?? false) {
            return; // déjà défini
        }

        $relativePath = '/r/' . $enseigne->getUuid();
        try {
            $tracking = $this->urlGenerator->generate('qr_redirect', ['uuid' => $enseigne->getUuid()], UrlGeneratorInterface::ABSOLUTE_URL);
        } catch (\Throwable) {
            $tracking = $relativePath;
        }
        $enseigne->setTrackingUrl($tracking);

        // Génère le QR code avant insertion (uuid suffisant)
        $filename = $enseigne->getUuid() . '.png';
        $qrRelative = $this->qrCodeGenerator->generate($tracking, $filename, $enseigne->getName());
        $enseigne->setQrFilename($qrRelative);
        // Pas de flush/persist ici : Doctrine fera l'INSERT avec les valeurs mises à jour
    }
}
