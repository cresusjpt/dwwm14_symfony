<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Payment;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Stripe\Stripe;
use \Stripe\Checkout\Session as StripeSession;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'stripe')]
    public function index(Session $session, ProductRepository $productRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        Stripe::setApiKey($this->getParameter('stripe_key'));

        $order = new Order();
        $order->setState(false);
        $order->setCreatedAt(new DateTimeImmutable());
        $order->setCustomer($this->getUser()->getCustomer());

        $entityManagerInterface->persist($order);

        $payment = new Payment();
        $payment->setCreatedAt(new DateTimeImmutable());
        $payment->setState(false);
        $payment->setAmount(0);
        $payment->setPaid($order);

        $entityManagerInterface->persist($payment);

        $line_items = [];

        $cart = $session->get($this->getParameter('cart_variable'), []);
        foreach ($cart as $detail) {
            $detail->setDetailOrder($order);
            $product = $productRepository->find($detail->getDetailProduct()->getId());
            $detail->setDetailProduct($product);
            $detail->setAmount($detail->getQte() * $detail->getDetailProduct()->getPrice());

            $entityManagerInterface->persist($detail);
            $entityManagerInterface->flush();

            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $detail->getDetailProduct()->getName(),
                    ],
                    'unit_amount' => $detail->getDetailProduct()->getPrice() * 100,
                ],
                'quantity' => $detail->getQte(),
            ];
        }

        $stripe_session = StripeSession::create([
            'line_items' => [$line_items],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('stripe-success', [
                'id' => $order->getId(),
            ], 0),
            'cancel_url' => $this->generateUrl('stripe-cancel', [
                'id' => $order->getId(),
            ], 0),
        ]);

        return $this->redirect($stripe_session->url);
    }


    #[Route('/stripe-success/{id}', name: 'stripe-success')]
    public function success(Order $order, EntityManagerInterface $entityManagerInterface): Response
    {
        $details = $order->getDetails();
        $payment = $order->getPayments()[0];

        $order->setState(true);
        $payment->setState(true);

        $payment_amount = 0;

        foreach ($details as $detail) {
            $payment_amount += $detail->getQte() * $detail->getDetailProduct()->getPrice();
        }

        $payment->setAmount($payment_amount);
        $entityManagerInterface->flush();

        return $this->render('stripe/index.html.twig', [
            'order' => $order,
            'details' => $details,
            'payment' => $payment,
            'route' => 'SUCCESS'
        ]);
    }

    #[Route('/stripe-cancel/{id}', name: 'stripe-cancel')]
    public function cancel(Order $order): Response
    {
        $details = $order->getDetails();
        $payment = $order->getPayments()[0];

        return $this->render('stripe/index.html.twig', [
            'order' => $order,
            'details' => $details,
            'payment' => $payment,
            'route' => 'CANCEL'
        ]);
    }
}
