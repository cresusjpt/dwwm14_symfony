<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


//.yaml
//sur les controller
//soit sur les fonction des controller
//à l'intérieur des fonctions
//dans les vues
// Admin
// client
// clientvip
//client






class HomeController extends AbstractController
{

    //clientvip
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home Function',
        ]);
    }

    #[Route('/hello', name: 'hello')]
    /**
     * @IsGranted("ROLE_CLIENT_VIP")
     */
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
