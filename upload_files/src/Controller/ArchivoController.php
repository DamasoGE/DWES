<?php

namespace App\Controller;

use App\Entity\Archivo;
use App\Form\ArchivoType;
use App\Repository\ArchivoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/archivo')]
final class ArchivoController extends AbstractController
{
    #[Route(name: 'app_archivo_index', methods: ['GET'])]
    public function index(ArchivoRepository $archivoRepository): Response
    {
        return $this->render('archivo/index.html.twig', [
            'archivos' => $archivoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_archivo_new', methods: ['GET', 'POST'])]
    public function new(
                        Request $request, 
                        EntityManagerInterface $entityManager,
                        SluggerInterface $slugger,
                        #[Autowire('%kernel.project_dir%/public/uploads/')] string $uploadsDirectory
    ): Response
    {
        $archivo = new Archivo();
        $form = $this->createForm(ArchivoType::class, $archivo);
        $form->handleRequest($request);

        $archivoFile = $form->get('archivo')->getData();

        if ($form->isSubmitted() && $form->isValid() && $archivoFile) {

            $originalFilename = pathinfo($archivoFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$archivoFile->guessExtension();

            try {
                $archivoFile->move($uploadsDirectory, $newFilename);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $archivo-> setFilename($newFilename);

            $partes = explode(".", $newFilename,2);

            $archivo-> setName($partes[0]);
            $archivo-> setExtension($partes[1]);


            $entityManager->persist($archivo);
            $entityManager->flush();

            return $this->redirectToRoute('app_archivo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('archivo/new.html.twig', [
            'archivo' => $archivo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_archivo_show', methods: ['GET'])]
    public function show(Archivo $archivo): Response
    {
        return $this->render('archivo/show.html.twig', [
            'archivo' => $archivo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_archivo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Archivo $archivo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArchivoType::class, $archivo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_archivo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('archivo/edit.html.twig', [
            'archivo' => $archivo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_archivo_delete', methods: ['POST'])]
    public function delete(Request $request, Archivo $archivo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$archivo->getId(), $request->getPayload()->getString('_token'))) {

            //PARA BORRAR TAMBIEN EL ARCHIVO DE LA CARPETA UPLOADS
            $filesystem = new Filesystem();

            $rutaArchivo = $this->getParameter('kernel.project_dir') . '/public/uploads/'.$archivo -> getFilename();

            try {
                if ($filesystem->exists($rutaArchivo)) {
                    $filesystem->remove($rutaArchivo);
                }
            } catch (IOExceptionInterface $exception) {
                echo "Error eliminando el archivo: " . $exception->getMessage();
            }

            $entityManager->remove($archivo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_archivo_index', [], Response::HTTP_SEE_OTHER);
    }
}
