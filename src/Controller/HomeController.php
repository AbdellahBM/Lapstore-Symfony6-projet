<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


    private $productRepository ;
    private $categoryRepository ;
    private $entityManager ;

    public function __construct(ProductRepository $productRepository,
                                EntityManagerInterface $doctrine ,
                                CategoryRepository $categoryRepository 
                                )
    {
        $this->productRepository = $productRepository ;
        $this->entityManager = $doctrine ;
        $this->categoryRepository = $categoryRepository ; 
    }




    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'categories' => $categories, 
        ]);
    }




    #[Route('/home/{category}', name: 'app_home_category')]
    public function byCategory (Category $category): Response
    {
        $products = $this->productRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        return $this->render('home/index.html.twig', [
            'products' => $category->getProducts(),
            'categories' => $categories, 

        ]);
    }

    
}


