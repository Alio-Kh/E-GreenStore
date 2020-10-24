<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categorie/{id}", name="category")
     */
    public function index(Categorie $categorie,CategorieRepository $categorieRepository)
    {
        
        $categories = $categorieRepository->findCategoryNotEmpty();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'categorie','categorie'=>$categorie,'categories'=>$categories
        ]);
    }
}
