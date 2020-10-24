<?php

namespace App\Controller;

use App\Repository\BlogRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(PaginatorInterface $paginator, CategorieRepository $categorieRepository, Request $request, ProduitRepository $produitRepository)
    {
        $categories = $categorieRepository->findCategoryNotEmpty();
        $produits = $produitRepository->findByLibelle($request->query->get('query'));
        $produits = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('search/index.html.twig', [
            'controller_name' => 'recherche', 'produits' => $produits, 'categories' => $categories
        ]);
    }

    /**
     * @Route("/blog/search", name="search_blog")
     */
    public function blog(Request $request,TagRepository $tagRepository, PaginatorInterface $paginator,CategorieRepository $categorieRepository,BlogRepository $blogRepository)
    {
        $categories=$categorieRepository->findCategoryNotEmpty();
        $blogs=$blogRepository->findByContent($request->query->get('query'));
        $tags=$tagRepository->findAll();
        $categoriesBlog= $categorieRepository->findBlogByPublished();
        $blogs = $paginator->paginate(
            $blogs,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('blog/search.html.twig', [
            'controller_name' => 'blog',
            'categories'=>$categories,
            'categoriesBlog'=>$categoriesBlog,
            'blogs'=>$blogs,
            'tags'=>$tags
        ]);
    }
}
