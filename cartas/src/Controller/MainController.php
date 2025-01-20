<?php

namespace App\Controller;

use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/{id_machine}/{id_user}', name: 'app_main_result')]
    public function result($id_machine, $id_user, CardRepository $cardRepository): Response
    {

        $machineCard = $cardRepository->find($id_machine);
        $userCard = $cardRepository->find($id_user);
        $result = '';

        

        if (!$machineCard || !$userCard) {
            return $this->render('404.html.twig');
        }

        if($userCard->getNumber() > $machineCard-> getNumber()){
            $result = "Has ganado";
        }else{
            $result = "Has perdido";
        }
        return $this->render('main/result.html.twig', [
            'controller_name' => 'MainController',
            'machineCard' => $machineCard,
            'userCard' => $userCard,
            'result' => $result
        ]);
    }

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
