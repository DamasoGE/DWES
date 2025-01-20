<?php

namespace App\Controller;

use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{

    #[Route('/', name: 'app_main')]
    public function index(CardRepository $cardRepository): Response
    {

        $cards = $cardRepository -> gameCards(3);
        $machineCard = $cards[0];
        array_shift($cards);
        $userCards = $cards;
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'machineCard' => $machineCard,
            'userCards' => $userCards
        ]);
    }


}
