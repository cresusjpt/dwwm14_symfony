<?php

namespace App\Controller;

use App\Entity\Detail;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    #[Route('/cart/{id}', name: 'cart')]
    public function index(Product $product, Session $session): Response
    {
        $detail = new Detail();
        $detail->setQte(1);
        $detail->setDetailProduct($product);

        $cart = $session->get($this->getParameter('cart_variable'), []);

        foreach ($cart as $det) {
            if ($det->getDetailProduct()->getId() == $detail->getDetailProduct()->getId()) {
                return $this->redirectToRoute('cart-size');
            }
        }

        $cart[] = $detail;
        $session->set($this->getParameter('cart_variable'), $cart);
        return $this->redirectToRoute('cart-size');
    }

    #[Route('/cart-list', name: 'cart-list')]
    public function listCart(Session $session): Response
    {
        $cart = $session->get($this->getParameter('cart_variable'), []);

        return $this->render('cart/listcart.html.twig', [
            'details' => $cart
        ]);
    }

    // ajouter au panier
    // je recalcul sa taille
    // afficher cette taille

    #[Route('/count', name: 'cart-size')]
    public function getCartCount(Session $session): Response
    {
        $cart = $session->get($this->getParameter('cart_variable'), []);
        $size = count($cart);

        $cookie = Cookie::create('size', $size, time() * 3600);

        $response = $this->redirectToRoute('home');
        $response->headers->setCookie($cookie);

        return $response;
    }
}
