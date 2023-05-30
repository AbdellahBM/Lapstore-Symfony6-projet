<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductsFormType;
use App\Repository\ProductRepository;
//use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    private $productRepository ;
    private $entityManager ;

    public function __construct(ProductRepository $productRepository,
                                EntityManagerInterface $doctrine )
    {
        $this->productRepository = $productRepository ;
        $this->entityManager = $doctrine ;
    }



    


    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        #recupere tous les produit dans la variable $products
        $products = $this->productRepository->findAll();  
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
        ]);
    }

    










    #[Route('/product/add', name: 'app_product_add')]
    public function add(Request $request): Response 
    {
        $product = new Product();
        $form = $this->createForm(ProductsFormType::class , $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $product = $form->getData();
            if($request->files->get('products_form')['image'])
            {
                $image = $request->files->get('products_form')['image'];
                $image_name = time().'_'. $image->getClientOriginalName();
                $image->move($this->getParameter('image_directory'),$image_name);
                $product->setImage($image_name);
            }

            $this->entityManager->persist($product);
            $this->entityManager->flush(); 
            $this->addFlash('success','your product was saved');
            return $this->redirectToroute('app_product');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form ,
            
        ]);
    }










    

    #[Route('/product/details/{id}', name: 'app_product_details_id')]
    public function show(Product $product): Response
    {
        #recupere tous les produit dans la variable $products
        return $this->render('product/show.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product  ,
        ]);
    }
















    

    #[Route('/product/edit/{id}', name: 'app_product_edit_id')]
    public function edit(Product $product ,Request $request): Response
    {
        $form = $this->createForm(ProductsFormType::class , $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $product = $form->getData();
            if($request->files->get('products_form')['image'])
            {
                $image = $request->files->get('products_form')['image'];
                $image_name = time().'_'. $image->getClientOriginalName();
                $image->move($this->getParameter('image_directory'),$image_name);
                $product->setImage($image_name);
            }

            $this->entityManager->persist($product);
            $this->entityManager->flush(); 
            $this->addFlash('success','your product was updated');
            return $this->redirectToroute('app_product');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form ,
            'product' => $product ,
        ]);
    }




    #[Route('/product/delet/{id}', name: 'app_product_delet_id')]
    public function delet(Product $product): Response
    {
        $filesystem = new Filesystem();
        $imagePath = './uploads/'.$product->getImage();
        if($filesystem->exists($imagePath)){
            $filesystem->remove($imagePath);
        }
        $this->entityManager->remove($product);
        $this->entityManager->flush(); 
        $this->addFlash('success','your product was deleted');
        return $this->redirectToroute('app_product');


    }

}
 