<?php

namespace App\Controller;

use App\Entity\Enseigne;
use App\Entity\QrScan;
use App\Repository\EnseigneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class QrRedirectController extends AbstractController
{
    #[Route('/r/{uuid}', name: 'qr_redirect', methods: ['GET'])]
    public function track(
        string $uuid,
        Request $request,
        EnseigneRepository $enseigneRepository,
        EntityManagerInterface $em
    ): Response {
        /** @var Enseigne|null $enseigne */
        $enseigne = $enseigneRepository->findOneBy(['uuid' => $uuid]);
        if (!$enseigne) {
            return new Response('QR code inconnu', 404);
        }

        $userAgent = $request->headers->get('User-Agent', '');
        $device = $this->detectDevice($userAgent);
        $ip = $request->getClientIp();

        $scan = new QrScan();
        $scan->setEnseigne($enseigne)
            ->setUserAgent($userAgent)
            ->setDeviceType($device)
            ->setIpAddress($ip);

        $em->persist($scan);
        $em->flush();

        // Pour l'instant redirige vers le site d'accueil
        return new RedirectResponse('/');
    }

    private function detectDevice(string $ua): string
    {
        $uaLower = strtolower($ua);
        return match (true) {
            str_contains($uaLower, 'mobile') => 'mobile',
            str_contains($uaLower, 'tablet') || str_contains($uaLower, 'ipad') => 'tablet',
            str_contains($uaLower, 'windows') || str_contains($uaLower, 'macintosh') || str_contains($uaLower, 'linux') => 'desktop',
            default => 'other'
        };
    }
}
