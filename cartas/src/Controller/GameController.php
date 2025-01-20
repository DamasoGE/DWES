<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\CardRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game')]
final class GameController extends AbstractController
{

    #[Route('/new', name: 'app_game_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CardRepository $cardRepository, ): Response
    {

        if($request->isMethod('POST') && $request-> request -> get('card')){
            $idCard = $request-> request -> get('card');
            $card0 = $cardRepository -> find($idCard);

            if ($card0) {
                $user0 = $this -> getUser();
                $game = new Game();
    
                $game -> setUser0($user0);
                $game -> setCard0($card0);
    
                $entityManager->persist($game);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
            }
        }else{
            $gameCards =  $cardRepository -> gameCards(3);

            return $this->render('game/new.html.twig', [
                'gameCards' => $gameCards,
            ]);
        }
    }

    #[Route('/{idGame}', name: 'app_game_play', methods: ['GET','POST'])]
    public function playCard($idGame,  Request $request, EntityManagerInterface $entityManager, CardRepository $cardRepository, GameRepository $gameRepository): Response
    {
        $game = $gameRepository -> find($idGame);

        if($request->isMethod('POST') && $request-> request -> get('card')){

            $idCard = $request-> request -> get('card');
            $card1 = $cardRepository -> find($idCard);

            if ($game && $card1) {
                $user1 = $this -> getUser();
    
                $game -> setUser1($user1);
                $game -> setCard1($card1);

                $game -> getCard0() -> getNumber() > $game ->getCard1() -> getNumber() ? $game -> setWinner(0) : $game -> setWinner(1);
    
                $entityManager->persist($game);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_game_index', [
                    'finished' => 1,
                ], Response::HTTP_SEE_OTHER);
            }
        }else{
            $game = $gameRepository -> find($idGame);
            $gameCards =  $cardRepository -> gameCards(3);

            return $this->render('game/play.html.twig', [
                'gameCards' => $gameCards,
                'game' => $game
            ]);
        }
    }


    #[Route('/', name: 'app_game_index', methods: ['GET'])]
    public function index(Request $request, GameRepository $gameRepository): Response
    {
        $finished = $request->query->get('finished');

        $games = $gameRepository->findAll();

        if($finished){

            $games = array_filter($games, function ($game) {
                return $game->getWinner() !== null && ($game->getUser0() == $this ->getUser() || $game->getUser1() == $this ->getUser()) ;
            });

            $encabezado = "Partidas jugadas";

            return $this->render('game/index.html.twig', [
                'encabezado' => $encabezado,
                'games' => $games,
            ]);
        }else{

            $games = array_filter($games, function ($game) {
                return $game->getUser0() !== $this -> getUser() && $game->getWinner() === null;
            });

            $encabezado = "Partidas disponibles";

            return $this->render('game/index.html.twig', [
                'encabezado' => $encabezado,
                'games' => $games,
            ]);
        }

    }


    // #[Route('/{id}', name: 'app_game_show', methods: ['GET'])]
    // public function show(Game $game): Response
    // {
    //     return $this->render('game/show.html.twig', [
    //         'game' => $game,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_game_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(GameType::class, $game);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('game/edit.html.twig', [
    //         'game' => $game,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_game_delete', methods: ['POST'])]
    // public function delete(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($game);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    // }


}
