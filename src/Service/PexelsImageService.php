<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PexelsImageService
{
    private const PEXELS_API_URL = 'https://api.pexels.com/v1/search';
    private const FALLBACK_IMAGES = [
        'https://images.pexels.com/photos/3183150/pexels-photo-3183150.jpeg',
        'https://images.pexels.com/photos/3183153/pexels-photo-3183153.jpeg',
        'https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg',
        'https://images.pexels.com/photos/3184296/pexels-photo-3184296.jpeg',
        'https://images.pexels.com/photos/3184338/pexels-photo-3184338.jpeg',
        'https://images.pexels.com/photos/3184339/pexels-photo-3184339.jpeg',
        'https://images.pexels.com/photos/3184340/pexels-photo-3184340.jpeg',
        'https://images.pexels.com/photos/3184341/pexels-photo-3184341.jpeg',
        'https://images.pexels.com/photos/3184342/pexels-photo-3184342.jpeg',
        'https://images.pexels.com/photos/3184343/pexels-photo-3184343.jpeg'
    ];

    public function __construct(
        private ParameterBagInterface $parameterBag,
        private HttpClientInterface $httpClient
    ) {
    }

    public function getRandomImage(string $query): string
    {
        try {
            $pexelsApiKey = $this->parameterBag->get('PEXELS_API_KEY');
            
            if (!$pexelsApiKey) {
                return $this->getFallbackImage();
            }

            // Nettoyer et optimiser la requête
            $searchQuery = $this->optimizeSearchQuery($query);
            
            $response = $this->httpClient->request('GET', self::PEXELS_API_URL, [
                'headers' => [
                    'Authorization' => $pexelsApiKey
                ],
                'query' => [
                    'query' => $searchQuery,
                    'per_page' => 10,
                    'orientation' => 'landscape'
                ]
            ]);

            $data = $response->toArray();

            if (isset($data['photos']) && !empty($data['photos'])) {
                // Choisir une image aléatoire parmi les résultats
                $randomPhoto = $data['photos'][array_rand($data['photos'])];
                return $randomPhoto['src']['large'];
            }

        } catch (\Exception $e) {
            // En cas d'erreur, utiliser une image de fallback
        }

        return $this->getFallbackImage();
    }

    private function optimizeSearchQuery(string $query): string
    {
        // Mapper les sujets vers des mots-clés d'images plus génériques
        $queryMappings = [
            'entreprise' => 'business',
            'digital' => 'digital technology',
            'web' => 'web development',
            'marketing' => 'marketing',
            'seo' => 'seo optimization',
            'design' => 'design',
            'technologie' => 'technology',
            'business' => 'business',
            'site' => 'website',
            'application' => 'app development',
            'mobile' => 'mobile app',
            'e-commerce' => 'ecommerce',
            'landing page' => 'landing page',
            'référencement' => 'seo',
            'conversion' => 'conversion',
            'client' => 'customer service',
            'stratégie' => 'strategy',
            'innovation' => 'innovation',
            'startup' => 'startup',
            'pme' => 'small business'
        ];

        $query = strtolower($query);
        
        foreach ($queryMappings as $french => $english) {
            if (strpos($query, $french) !== false) {
                return $english;
            }
        }

        // Si aucun mapping trouvé, utiliser des mots-clés génériques
        return 'business technology';
    }

    private function getFallbackImage(): string
    {
        return self::FALLBACK_IMAGES[array_rand(self::FALLBACK_IMAGES)];
    }
}
