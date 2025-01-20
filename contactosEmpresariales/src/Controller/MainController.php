<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        //  FORMA ALTERNATIVA
        $who = 'damaso';
        $controller_name = 'MainController';
        return $this->render('main/index.html.twig', compact('who','controller_name'));

        //compact tranforma varias variables en un solo array

        /* LA FORMA CLÁSICA
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'who' => 'Damaso',
        ]);
        */
    }
}
