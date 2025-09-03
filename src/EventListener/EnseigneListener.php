<?php

namespace App\EventListener;

use App\Entity\Enseigne;
use App\Service\QrCodeGenerator;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Enseigne::class)]
class EnseigneListener
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private QrCodeGenerator $qrCodeGenerator,
    ) {}

    public function postPersist(Enseigne $enseigne, PostPersistEventArgs $args): void
    {
        // Si déjà initialisé, on évite boucle
        if ($enseigne->getTrackingUrl() ?? false) {
            return;
        }

        $relativePath = '/r/' . $enseigne->getUuid();
        // Génération URL absolue si possible (router context), sinon relative
        try {
            $tracking = $this->urlGenerator->generate('qr_redirect', ['uuid' => $enseigne->getUuid()], UrlGeneratorInterface::ABSOLUTE_URL);
        } catch (\Throwable) {
            $tracking = $relativePath;
        }
        $enseigne->setTrackingUrl($tracking);

        $filename = $enseigne->getUuid() . '.png';
        $qrRelative = $this->qrCodeGenerator->generate($tracking, $filename, $enseigne->getName());
        $enseigne->setQrFilename($qrRelative);

        $em = $args->getObjectManager();
        $em->persist($enseigne);
        $em->flush();
    }
}
