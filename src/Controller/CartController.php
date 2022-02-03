<?php

namespace App\Controller;

use App\Entity\Detail;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
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

    #[Route('/cart-remove/{id}', name: 'cart-remove')]
    public function remove(Product $product, Session $session): Response
    {
        $cart = $session->get($this->getParameter('cart_variable'), []);
        foreach ($cart as $key => $detail) {
            if ($detail->getDetailProduct()->getId() == $product->getId()) {
                unset($cart[$key]);
            }
        }

        $session->set($this->getParameter('cart_variable'), $cart);
        return $this->redirectToRoute('cart-size');
    }

    #[Route('/cart-plus/{id}', name: 'cart-plus')]
    public function plus(Product $product, Session $session): Response
    {
        $cart = $session->get($this->getParameter('cart_variable'), []);
        foreach ($cart as $detail) {
            if ($detail->getDetailProduct()->getId() == $product->getId()) {
                $detail->setQte($detail->getQte() + 1);
            }
        }

        $session->set($this->getParameter('cart_variable'), $cart);
        return $this->redirectToRoute('cart-size');
    }

    #[Route('/cart-minus/{id}', name: 'cart-minus')]
    public function minus(Product $product, Session $session): Response
    {
        $cart = $session->get($this->getParameter('cart_variable'), []);
        foreach ($cart as $key => $detail) {
            if ($detail->getDetailProduct()->getId() == $product->getId()) {
                $detail->setQte($detail->getQte() - 1);
                if ($detail->getQte() == 0) {
                    unset($cart[$key]);
                }
            }
        }

        $session->set($this->getParameter('cart_variable'), $cart);
        return $this->redirectToRoute('cart-size');
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

        $response = $this->redirectToRoute('cart-list');
        $response->headers->setCookie($cookie);

        return $response;
    }
}
