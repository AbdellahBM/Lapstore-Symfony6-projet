<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{

    private $orderRepository ;
    private $entityManager ;

    public function __construct( OrderRepository $orderRepository,
                                EntityManagerInterface $doctrine )
    {
        $this->orderRepository = $orderRepository ;
        $this->entityManager = $doctrine ;
    }





    
    /*
    #[Route('/order', name: 'app_order')]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }
    */

    #[Route('/order', name: 'app_order')]
    public function order(Product $product , Request $request): Response 
    {
        if(!$this->getUser())
        {
            return $this->redirectToroute('app_login');
        }
        $order = new Order ;
        $order->setPname($product->getName());
        $order->setPrice($product->getPrice());
        $order->setStatus('Proccesing...');
        $order->setUser($this->getUser());

        

        $this->entityManager->persist($order);
        $this->entityManager->flush(); 
        $this->addFlash('success','your order was saved');
       

        return $this->render('order/index.html.twig', [
            'product' => $product ,            
        ]);
    }

}
