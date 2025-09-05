<?php

namespace App\Controller;

use App\Entity\Enseigne;
use App\Entity\QrScan;
use App\Entity\QrShare;
use App\Repository\EnseigneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Service\ContactCardProvider;
use Symfony\Component\Routing\Annotation\Route;

class BusinessCardController extends AbstractController
{
    #[Route('/c/{uuid}', name: 'business_card', methods: ['GET'])]
    public function card(string $uuid, Request $request, EnseigneRepository $repo, EntityManagerInterface $em, \App\Service\ContactCardProvider $contactCardProvider): Response
    {
        $enseigne = $repo->findOneBy(['uuid' => $uuid]);
        if (!$enseigne) {
            throw $this->createNotFoundException();
        }
        // Enregistrer un scan (si pas déjà fait par ancienne route /r)
        $scan = new QrScan();
        $scan->setEnseigne($enseigne)
            ->setUserAgent($request->headers->get('User-Agent',''))
            ->setDeviceType($this->detectDevice($request->headers->get('User-Agent','')))
            ->setIpAddress($request->getClientIp());
        $em->persist($scan);
        $em->flush();

        return $this->render('business_card/card.html.twig', [
            'enseigne' => $enseigne,
            'contact' => $contactCardProvider->getVcardData(),
        ]);
    }

    #[Route('/c/{uuid}/vcard', name: 'business_card_vcard', methods: ['GET'])]
    public function vcard(string $uuid, EnseigneRepository $repo, ContactCardProvider $contactCardProvider): Response
    {
        $enseigne = $repo->findOneBy(['uuid' => $uuid]);
        if (!$enseigne) { throw $this->createNotFoundException(); }
        $data = $contactCardProvider->getVcardData();
        $firstName = $data['firstName'] ?? '';
        $lastName = $data['lastName'] ?? '';
        $org = $data['org'] ?? '';
        $title = $data['title'] ?? '';
        $email = $data['email'] ?? '';
        $phone = $data['phone'] ?? '';
        $website = $data['website'] ?? '';

        $lines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'N:'.$lastName.';'.$firstName.';;;',
            'FN:'.$firstName.' '.$lastName,
            'ORG:'.$org,
            'TITLE:'.$title,
            'TEL;TYPE=cell:'.$phone,
            'EMAIL;TYPE=internet,pref:'.$email,
            'URL:'.$website,
            'END:VCARD'
        ];
        $content = implode("\r\n", $lines) . "\r\n";

        return new Response($content, 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => (new ResponseHeaderBag())->makeDisposition('attachment', 'contact-'.$enseigne->getUuid().'.vcf')
        ]);
    }

    #[Route('/c/{uuid}/share/{channel}', name: 'business_card_share', methods: ['POST'])]
    public function share(string $uuid, string $channel, EnseigneRepository $repo, EntityManagerInterface $em): Response
    {
        $enseigne = $repo->findOneBy(['uuid' => $uuid]);
        if (!$enseigne) { return new Response('Not found', 404); }
        $share = new QrShare();
        $share->setEnseigne($enseigne)->setChannel($channel);
        $em->persist($share); $em->flush();
        return new Response('OK');
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
