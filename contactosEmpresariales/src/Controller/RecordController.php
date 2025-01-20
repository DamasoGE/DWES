<?php

namespace App\Controller;

use App\Entity\Record;
use App\Form\RecordType;
use App\Repository\RecordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/record')]
final class RecordController extends AbstractController
{
    #[Route(name: 'app_record_index', methods: ['GET'])]
    public function index(RecordRepository $recordRepository): Response
    {
        return $this->render('record/index.html.twig', [
            'records' => $recordRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_record_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $record = new Record();
        $form = $this->createForm(RecordType::class, $record);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($record);
            $entityManager->flush();

            return $this->redirectToRoute('app_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('record/new.html.twig', [
            'record' => $record,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_record_show', methods: ['GET'])]
    public function show(Record $record): Response
    {
        return $this->render('record/show.html.twig', [
            'record' => $record,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_record_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Record $record, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecordType::class, $record);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('record/edit.html.twig', [
            'record' => $record,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_record_delete', methods: ['POST'])]
    public function delete(Request $request, Record $record, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$record->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($record);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_record_index', [], Response::HTTP_SEE_OTHER);
    }
}
