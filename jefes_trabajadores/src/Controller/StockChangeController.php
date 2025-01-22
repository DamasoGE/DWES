<?php

namespace App\Controller;

use App\Entity\StockChange;
use App\Form\StockChangeType;
use App\Repository\StockChangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/stockchange')]
final class StockChangeController extends AbstractController
{
    #[Route(name: 'app_stockchange_index', methods: ['GET'])]
    public function index(Request $request, StockChangeRepository $stockChangeRepository): Response
    {
        $info = $request->query->get("info");
        return $this->render('stock_change/index.html.twig', [
            'stock_changes' => $stockChangeRepository->findAll(),
            'info' => $info
        ]);
    }

    #[Route('/new', name: 'app_stockchange_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stockChange = new StockChange();
        $form = $this->createForm(StockChangeType::class, $stockChange);
        $form->handleRequest($request);

        $info="";

        if ($form->isSubmitted() && $form->isValid()) {

            $item = $stockChange->getItem();


            if($stockChange->getStockChange()<1 && $item->getAmount()< (-1)*$stockChange->getStockChange()){
                $info= "INFO: No hay stock suficiente";
            }else{
                $item-> setAmount($item->getAmount()+$stockChange->getStockChange());

                $entityManager->persist($stockChange);
                $entityManager->persist($item);
                $entityManager->flush();
            }


            return $this->redirectToRoute('app_stockchange_index', [
                'info' => $info,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stock_change/new.html.twig', [
            'stock_change' => $stockChange,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stockchange_show', methods: ['GET'])]
    public function show(StockChange $stockChange): Response
    {
        return $this->render('stock_change/show.html.twig', [
            'stock_change' => $stockChange,
        ]);
    }

    #[Route('/{id}', name: 'app_stockchange_delete', methods: ['POST'])]
    public function delete(Request $request, StockChange $stockChange, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stockChange->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($stockChange);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stockchange_index', [], Response::HTTP_SEE_OTHER);
    }
}
