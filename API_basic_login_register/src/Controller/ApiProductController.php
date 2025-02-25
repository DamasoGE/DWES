<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/product')]
class ApiProductController extends AbstractController
{
    #[Route(name: 'app_api_product_index', methods: ['GET'])]
    public function getProducts(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $products = $productRepository->findAll();

        // Serializa los productos a JSON
        $jsonContent = $serializer->serialize($products, 'json', ['groups' => 'product:read']);

        return new JsonResponse($jsonContent, 200, ['Content-Type' => 'application/json'], true);
    }

    #[Route(name: 'app_api_product_create', methods: ['POST'])]
    public function createProduct(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        // Obtén los datos de la petición
        $data = json_decode($request->getContent(), true);

        // Deserializa los datos en un objeto Product
        $product = $serializer->deserialize(json_encode($data), Product::class, 'json', ['groups' => 'product:write']);

        // Guarda el producto en la base de datos
        $entityManager->persist($product);
        $entityManager->flush();

        //Serializa el producto que hemos insertado
        $jsonContent = $serializer->serialize($product, 'json', ['groups' => 'product:read']);

        // Responde con el nuevo producto en formato JSON
        return new JsonResponse($jsonContent, 200, ['Content-Type' => 'application/json'], true);
    }

    #[Route('/{id}', name: 'app_api_product_show', methods: ['GET'])]
    public function getProduct(int $id, ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        // Serializa el producto a JSON
        $jsonContent = $serializer->serialize($product, 'json', ['groups' => 'product:read']);

        return new JsonResponse($jsonContent, 200, ['Content-Type' => 'application/json'], true);
    }

    #[Route('/{id}', name: 'app_api_product_delete', methods: ['DELETE'])]
    public function deleteProduct(int $id, ProductRepository $productRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        // Elimina el producto de la base de datos
        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Product deleted'], 200);
    }

    #[Route('/{id}', name: 'app_api_product_update', methods: ['PATCH'])]
    public function updateProduct(int $id, Request $request, ProductRepository $productRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        // Obtén los datos de la petición
        $data = json_decode($request->getContent(), true);

        // Deserializa solo los datos que se proporcionan en la solicitud (no se sobrescriben todos los campos)
        $serializer->deserialize(json_encode($data), Product::class, 'json', [
            'object_to_populate' => $product,
            'groups' => 'product:write'
        ]);
        //Guarda los cambios
        $entityManager->flush();

        // Serializa el producto a JSON
        $jsonContent = $serializer->serialize($product, 'json', ['groups' => 'product:read']);

        return new JsonResponse($jsonContent, 200, ['Content-Type' => 'application/json'], true);
    }


}
