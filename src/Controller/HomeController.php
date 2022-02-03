<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository, Session $session): Response
    {
        $products = $productRepository->findAll();
        return $this->render('home/index.html.twig', [
            'produits' => $products,
        ]);
    }

    #[Route('/hello', name: 'hello')]
    public function helll(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Hello Function',
        ]);
    }

    #[Route('/world', name: 'world')]
    public function world(): Response
    {
        var_dump('Un message avant la restriction');

        $this->denyAccessUnlessGranted("ROLE_CLIENT_VIP");
        return $this->render('home/index.html.twig', [
            'controller_name' => 'World Function',
        ]);
    }
}
