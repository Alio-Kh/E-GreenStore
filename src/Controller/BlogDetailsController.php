<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\CategorieRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogDetailsController extends AbstractController
{
    /**
     * @Route("/blog/{id}/details", name="blog_details")
     */
    public function index(TagRepository $tagRepository,Blog $blog,CategorieRepository $categorieRepository)
    {
        $categories=$categorieRepository->findCategoryNotEmpty();
        $tags=$tagRepository->findAll();
        $categoriesBlog= $categorieRepository->findBlogByPublished();
        
        return $this->render('blog_details/index.html.twig', [
            'controller_name' => 'blog',
            'categories'=>$categories,
            'categoriesBlog'=>$categoriesBlog,
            'blog'=>$blog,
            'tags'=>$tags
        ]);
    }
}
