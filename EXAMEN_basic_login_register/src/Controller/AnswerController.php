<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Form\AnswerType;
use App\Repository\AnswerRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/answer')]
final class AnswerController extends AbstractController
{

    #[Route('/new/{id}', name: 'app_answer_new', methods: ['GET', 'POST'])]
    public function new(TicketRepository $ticketRepository, Security $security, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user= $this->getUser();
        $ticket = $ticketRepository -> find($id);
        $answer = new Answer();

        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {

            $text = $request->request->get('text'); 

            if ($text) {
                $answer->setText($text);
                $answer->setUser($user);
                $answer->setTicket($ticket);
                $answer->setCreatedAt(new \DateTimeImmutable());
            }

            $entityManager->persist($answer);
            $entityManager->flush();

            if($ticket->getEmployee()==null){
                $ticket->setEmployee($user);
                $ticket->setStatus(1);
                $entityManager->persist($user);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_ticket_show',
            ['id' => $id],
            Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('app_main');
    }

    // #[Route(name: 'app_answer_index', methods: ['GET'])]
    // public function index(AnswerRepository $answerRepository): Response
    // {
    //     return $this->render('answer/index.html.twig', [
    //         'answers' => $answerRepository->findAll(),
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_answer_show', methods: ['GET'])]
    // public function show(Answer $answer): Response
    // {
    //     return $this->render('answer/show.html.twig', [
    //         'answer' => $answer,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_answer_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Answer $answer, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(AnswerType::class, $answer);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('answer/edit.html.twig', [
    //         'answer' => $answer,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_answer_delete', methods: ['POST'])]
    // public function delete(Request $request, Answer $answer, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$answer->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($answer);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
    // }
}
