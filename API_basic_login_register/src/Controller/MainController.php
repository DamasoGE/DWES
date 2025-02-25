<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MainController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/take', name: 'app_take', methods: ['GET', 'POST'])]
    public function recoger(Request $request): Response
    {
        // Variable para los datos del formulario
        $name = '';
        $age = '';

        // Si el formulario es enviado (POST)
        if ($request->isMethod('POST')) {
            // Obtener los datos del formulario
            $name = $request->request->get('name');
            $age = $request->request->get('age');
            return $this->render('main/result.html.twig', [
                'name' => $name,
                'age' => $age,
            ]);
        }

        // Renderizar la vista
        return $this->render('main/take.html.twig', [
            'name' => $name,
            'age' => $age,
        ]);
    }


}
