<?php

namespace App\Controller\Admin;

use App\Entity\Posts;
use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin/blog')]
#[IsGranted('ROLE_ADMIN')]
class BlogController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'admin_blog_index', methods: ['GET'])]
    public function index(): Response
    {
        $posts = $this->entityManager
            ->getRepository(Posts::class)
            ->findBy([], ['createdAt' => 'DESC']);

        $categories = $this->entityManager
            ->getRepository(Categories::class)
            ->findAll();

        return $this->render('admin/blog/index.html.twig', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }
}
