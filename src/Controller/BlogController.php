<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\BlogCategoryRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    #[Route('/actualites', name: 'app_blog_index')]
    public function index(PostRepository $postRepository, BlogCategoryRepository $categoryRepository): Response
    {
        $posts = $postRepository->findPublished();
        $categories = $categoryRepository->findAll();

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    #[Route('/actualites/{slug}', name: 'app_blog_show')]
    public function show(string $slug, PostRepository $postRepository, BlogCategoryRepository $categoryRepository): Response
    {
        $post = $postRepository->findOneBy(['slug' => $slug, 'isPublished' => true]);

        if (!$post) {
            throw $this->createNotFoundException('Article introuvable');
        }

        $categories = $categoryRepository->findAll();
        $recentPosts = $postRepository->findPublished(3);

        return $this->render('blog/show.html.twig', [
            'post' => $post,
            'categories' => $categories,
            'recentPosts' => $recentPosts,
        ]);
    }

    #[Route('/actualites/categorie/{slug}', name: 'app_blog_category', priority: 2)]
    public function category(string $slug, BlogCategoryRepository $categoryRepository, PostRepository $postRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie introuvable');
        }

        $posts = $postRepository->findPublishedByCategory($category->getId());
        $categories = $categoryRepository->findAll();

        return $this->render('blog/category.html.twig', [
            'category' => $category,
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    #[Route('/actualites/tag/{slug}', name: 'app_blog_tag', priority: 2)]
    public function tag(string $slug, TagRepository $tagRepository, PostRepository $postRepository, BlogCategoryRepository $categoryRepository): Response
    {
        $tag = $tagRepository->findOneBy(['slug' => $slug]);

        if (!$tag) {
            throw $this->createNotFoundException('Tag introuvable');
        }

        $posts = $postRepository->findPublishedByTag($tag->getId());
        $categories = $categoryRepository->findAll();

        return $this->render('blog/tag.html.twig', [
            'tag' => $tag,
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }
}
