<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    #[IsGranted('ROLE_USER')]
    public function index(ItemRepository $itemRepository): Response
    {
        $items = $itemRepository->findAll();

        usort($items, function($a, $b) {
            return $a->getLocation() -> getHallway() <=> $b->getLocation() ->getHallway();
        });

        return $this->render('main/index.html.twig', [
            'items' => $items,
        ]);
    }

}
