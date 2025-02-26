<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\AnswerRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/ticket')]
final class TicketController extends AbstractController
{
    #[Route(name: 'app_ticket_index', methods: ['GET'])]
    public function index(Security $security, TicketRepository $ticketRepository): Response
    {
        $user = $this -> getUser();
        $roles = $user->getRoles();  

        $isAdmin = in_array("ROLE_ADMIN", $roles);
        $isEmployee = in_array("ROLE_EMPLOYEE", $roles);
        $isCustomer = in_array("ROLE_CLIENT", $roles);

        // Usar las variables booleanas como necesites
        if ($isAdmin) {
            $tickets = $ticketRepository->findAll();
        }

        if ($isEmployee) {
            $tickets = $ticketRepository->createQueryBuilder('t')
            ->where('t.employee = :user OR t.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 0)
            ->getQuery()
            ->getResult();
        }

        if ($isCustomer) {
            $tickets = $user->getTickets();

        }

        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets,
        ]);
    }

    #[Route('/new', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    public function new(Security $security, Request $request, EntityManagerInterface $entityManager): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $security->getUser();
            $ticket->setUser($user); 
          
            $ticket->setStatus(0);

            $ticket->setCreatedAt(new \DateTimeImmutable());

            $ticket->setClosedAt(null);

            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ticket_show', methods: ['GET'])]
    public function show(Ticket $ticket): Response
    {
        $answers = $ticket->getAnswers();

        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
            'answers' => $answers
        ]);
    }

    #[Route('/close/{id}', name: 'app_ticket_close', methods: ['POST'])]
    public function close(Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        
        $ticket->setStatus(2); 
        $ticket->setClosedAt(new \DateTime());
        
        $entityManager->flush();
        return $this->redirectToRoute('app_main');
    }

    #[Route('/rating/{id}', name: 'app_ticket_rating', methods: ['POST'])]
    public function rating(Ticket $ticket, Request $request, EntityManagerInterface $entityManager): Response
    {
        $rating = $request->request->get('rating');

        $ticket->setRating((int)$rating);

        $entityManager->flush();
    
        return $this->redirectToRoute('app_main');
    }

    // #[Route('/{id}/edit', name: 'app_ticket_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(TicketType::class, $ticket);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('ticket/edit.html.twig', [
    //         'ticket' => $ticket,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_ticket_delete', methods: ['POST'])]
    // public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($ticket);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    // }
}
