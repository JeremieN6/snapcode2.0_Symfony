<?php

namespace App\Service;

use App\Entity\Posts;
use App\Entity\Categories;
use App\Entity\Users;
use App\Repository\CategoriesRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Orhanerday\OpenAi\OpenAi;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class BlogArticleGeneratorService
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private EntityManagerInterface $entityManager,
        private CategoriesRepository $categoriesRepository,
        private UsersRepository $usersRepository,
        private SluggerInterface $slugger,
        private PexelsImageService $pexelsImageService
    ) {
    }

    public function generateArticle(string $subject, int $categoryId): ?Posts
    {
        try {
            // Récupérer la catégorie
            $category = $this->categoriesRepository->find($categoryId);
            if (!$category) {
                throw new \Exception('Catégorie non trouvée');
            }

            // Récupérer un utilisateur admin par défaut
            $user = $this->usersRepository->findOneBy(['roles' => ['ROLE_ADMIN']]);
            if (!$user) {
                $user = $this->usersRepository->findOneBy([]);
            }
            
            if (!$user) {
                throw new \Exception('Aucun utilisateur trouvé pour créer l\'article');
            }

            // Générer le contenu avec OpenAI
            $content = $this->generateContentWithOpenAI($subject);
            
            // Extraire le titre du contenu généré
            $title = $this->extractTitleFromContent($content);
            
            // Récupérer une image depuis Pexels
            $imageUrl = $this->pexelsImageService->getRandomImage($subject);
            
            // Insérer l'image dans le contenu
            $contentWithImage = $this->insertImageInContent($content, $imageUrl, $subject);

            // Créer l'article
            $post = new Posts();
            $post->setTitle($title);
            $post->setContent($contentWithImage);
            $post->setUsers($user);
            $post->setIsPublished(false); // Créer en brouillon
            $post->setIsFavorite(false);
            
            // Générer le slug automatiquement
            $slug = $this->slugger->slug($title)->lower();
            $post->setSlug($slug);

            // Sauvegarder l'article d'abord
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            
            // Pour l'instant, on ne gère pas les catégories pour éviter l'erreur de contrainte
            // TODO: Corriger la gestion des catégories plus tard
            // $post->addCategory($category);

            return $post;

        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la génération de l\'article: ' . $e->getMessage());
        }
    }

    private function generateContentWithOpenAI(string $subject): string
    {
        $openai_api_key = $this->parameterBag->get('OPENAI_API_KEY');
        $open_ai = new OpenAi($openai_api_key);

        $prompt = "Génère moi un article de blog avec comme sujet : $subject. Voici la structure à suivre : Le premier titre ne doit pas etre le meme que celui que je te donne. L'article fera entre 500 et 600 mots max. Ce sera un article mentionnant les meilleurs conseils que tu peux donner sur le sujet. Tu feras en sorte de manière subtile que c'est mon agence qui prodigue ses conseils et que si c'est un sujet qui est sensible pour le lecteur, il n'a qu'à contacter l'agence. Tu mettra un peu de contexte c'est à dire que tu fera comme si ce qui est dit dans cet article est une réelle histoire d'une entreprise française. Tu n'es pas obligé de présenter l'entreprise directement au début de l'article. Le premier h3 devra être un sous titre accrocheur de ce qui va être dit dans l'article. Ne mets pas des titres comme \"contexte\", \"conclusion\", des titres qui illustrent la structure de l'article. Ne mets pas la catégorie d'article que je te donne, dans le titre. L'article sera découpé entre 4 et 6 paragraphes pour rendre les textes plus digestes à la lecture. Enfin, les paragraphes auront des sous titres avec ce balisage (h3 avec comme classe : wp-block-heading). Les paragraphes seront dans des balises <p>. Utilise ton éditeur de code pour qu'on puisse voir si tu as bien mis les balises paragraphes, les balises h3 et les classes dans les balises h3. Pour illustrer tu ajouteras ce bout de code html suivant au milieu de l'article : Et à la place de description_image tu définiras ce que l'image représente en moins de 10 mots <figure class=\"wp-block-image alignwide size-large\"> <img decoding=\"async\" width=\"1024\" height=\"683\" src=\"add_url\" alt=\"\" class=\"wp-image-2338\" srcset=\"add_url\"> <figcaption class=\"wp-element-caption\">description_image</figcaption> </figure>";

        $complete = $open_ai->chat([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Tu es un expert en rédaction d\'articles de blog pour une agence web française. Tu écris des articles informatifs et engageants.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000,
        ]);

        $json = json_decode($complete, true);

        if (isset($json['choices'][0]['message']['content'])) {
            return trim($json['choices'][0]['message']['content']);
        }

        throw new \Exception('Erreur lors de la génération du contenu avec OpenAI');
    }

    private function extractTitleFromContent(string $content): string
    {
        // Chercher le premier titre h2 ou h1 dans le contenu
        if (preg_match('/<h[12][^>]*>(.*?)<\/h[12]>/i', $content, $matches)) {
            return strip_tags($matches[1]);
        }
        
        // Si pas de titre trouvé, prendre les premiers mots du contenu
        $text = strip_tags($content);
        $words = explode(' ', $text);
        $title = implode(' ', array_slice($words, 0, 8));
        
        return $title . '...';
    }

    private function insertImageInContent(string $content, string $imageUrl, string $subject): string
    {
        // Créer une description courte pour l'image
        $imageDescription = $this->generateImageDescription($subject);
        
        // Remplacer le placeholder dans le contenu
        $imageHtml = "<figure class=\"wp-block-image alignwide size-large\">
            <img decoding=\"async\" width=\"1024\" height=\"683\" src=\"$imageUrl\" alt=\"$imageDescription\" class=\"wp-image-2338\" srcset=\"$imageUrl\">
            <figcaption class=\"wp-element-caption\">$imageDescription</figcaption>
        </figure>";
        
        // Insérer l'image au milieu du contenu
        $paragraphs = explode('</p>', $content);
        $middleIndex = (int)(count($paragraphs) / 2);
        
        if (count($paragraphs) > 2) {
            $paragraphs[$middleIndex] .= $imageHtml;
            return implode('</p>', $paragraphs);
        }
        
        // Si pas assez de paragraphes, ajouter l'image après le premier paragraphe
        $count = 1;
        return str_replace('</p>', $imageHtml . '</p>', $content, $count);
    }

    private function generateImageDescription(string $subject): string
    {
        // Générer une description courte basée sur le sujet
        $descriptions = [
            'entreprise' => 'Entreprise moderne',
            'digital' => 'Transformation digitale',
            'web' => 'Développement web',
            'marketing' => 'Stratégie marketing',
            'seo' => 'Optimisation SEO',
            'design' => 'Design créatif',
            'technologie' => 'Innovation technologique',
            'business' => 'Stratégie business'
        ];
        
        foreach ($descriptions as $keyword => $description) {
            if (stripos($subject, $keyword) !== false) {
                return $description;
            }
        }
        
        return 'Conseils experts';
    }
}
