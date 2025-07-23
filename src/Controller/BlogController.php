<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Categories;
use App\Repository\PostsRepository;
use App\Repository\CategoriesRepository;
use App\Repository\KeywordsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'blog_index')]
    public function index(
        PostsRepository $postsRepository,
        CategoriesRepository $categoriesRepository
    ): Response {
        // Récupérer tous les articles publiés (limité à 12 pour l'instant)
        $posts = $postsRepository->findPublishedPosts(12);

        // Récupérer les catégories avec le nombre d'articles
        $categoriesWithCount = $categoriesRepository->findAllWithPostCount();

        // Récupérer les articles favoris
        $favoritePosts = $postsRepository->findFavoritePosts(3);

        // Récupérer les articles récents
        $recentPosts = $postsRepository->findThreeMostRecentPosts();

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'categories' => $categoriesWithCount,
            'favoritePosts' => $favoritePosts,
            'recentPosts' => $recentPosts,
            'currentPage' => 'blog'
        ]);
    }

    #[Route('/{slug}', name: 'blog_show', requirements: ['slug' => '[a-z0-9\-]+'])]
    public function show(
        string $slug,
        PostsRepository $postsRepository,
        CategoriesRepository $categoriesRepository
    ): Response {
        // Récupérer l'article par son slug
        $post = $postsRepository->findPublishedBySlug($slug);

        if (!$post) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        // Récupérer les articles similaires (même catégorie)
        $relatedPosts = [];
        if ($post->getCategories()->count() > 0) {
            $firstCategory = $post->getCategories()->first();
            $relatedPosts = $postsRepository->findByCategory($firstCategory->getSlug(), 3);
            
            // Exclure l'article actuel des articles similaires
            $relatedPosts = array_filter($relatedPosts, function($relatedPost) use ($post) {
                return $relatedPost->getId() !== $post->getId();
            });
        }

        // Récupérer les catégories pour la sidebar
        $categoriesWithCount = $categoriesRepository->findAllWithPostCount();

        return $this->render('blog/show.html.twig', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'categories' => $categoriesWithCount,
            'currentPage' => 'blog'
        ]);
    }

    #[Route('/category/{slug}', name: 'blog_category')]
    public function category(
        string $slug,
        PostsRepository $postsRepository,
        CategoriesRepository $categoriesRepository
    ): Response {
        // Récupérer la catégorie
        $category = $categoriesRepository->findBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        // Récupérer les articles de cette catégorie (limité à 12)
        $posts = $postsRepository->findByCategory($slug, 12);

        // Récupérer les catégories pour la sidebar
        $categoriesWithCount = $categoriesRepository->findAllWithPostCount();

        return $this->render('blog/category.html.twig', [
            'posts' => $posts,
            'category' => $category,
            'categories' => $categoriesWithCount,
            'currentPage' => 'blog'
        ]);
    }

    #[Route('/search', name: 'blog_search')]
    public function search(
        Request $request,
        PostsRepository $postsRepository,
        CategoriesRepository $categoriesRepository
    ): Response {
        $searchTerm = $request->query->get('q', '');

        if (empty($searchTerm)) {
            return $this->redirectToRoute('blog_index');
        }

        // Rechercher les articles (limité à 12)
        $posts = $postsRepository->searchPosts($searchTerm);

        // Récupérer les catégories pour la sidebar
        $categoriesWithCount = $categoriesRepository->findAllWithPostCount();

        return $this->render('blog/search.html.twig', [
            'posts' => $posts,
            'searchTerm' => $searchTerm,
            'categories' => $categoriesWithCount,
            'currentPage' => 'blog'
        ]);
    }
}
