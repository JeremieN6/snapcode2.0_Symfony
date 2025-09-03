<?php

namespace App\Controller\Admin;

use App\Service\BlogArticleGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/article-generator')]
#[IsGranted('ROLE_ADMIN')]
class ArticleGeneratorController extends AbstractController
{
    public function __construct(
        private BlogArticleGeneratorService $articleGeneratorService,
        private \Doctrine\ORM\EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'admin_article_generator', methods: ['GET'])]
    public function index(): Response
    {
        $categories = $this->entityManager
            ->getRepository(\App\Entity\Categories::class)
            ->findAll();

        return $this->render('admin/blog/blog_topic_form.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/generate', name: 'admin_article_generate', methods: ['POST'])]
    public function generate(Request $request): Response
    {
        $subject = $request->request->get('subject');
        $categoryId = $request->request->get('category');

        if (!$subject || !$categoryId) {
            $this->addFlash('error', 'Veuillez remplir tous les champs obligatoires.');
            return $this->redirectToRoute('admin_article_generator');
        }

        try {
            $article = $this->articleGeneratorService->generateArticle($subject, $categoryId);

            $this->addFlash('success', 'Article généré avec succès !');
            
            return $this->redirectToRoute('admin_article_show', [
                'id' => $article->getId()
            ]);

        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la génération de l\'article: ' . $e->getMessage());
            
            $categories = $this->entityManager
                ->getRepository(\App\Entity\Categories::class)
                ->findAll();

            return $this->render('admin/blog/blog_topic_form.html.twig', [
                'categories' => $categories,
                'error' => $e->getMessage(),
            ]);
        }
    }

    #[Route('/article/{id}', name: 'admin_article_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $article = $this->entityManager
            ->getRepository(\App\Entity\Posts::class)
            ->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        return $this->render('admin/article_generator/show.html.twig', [
            'article' => $article,
        ]);
    }
}
