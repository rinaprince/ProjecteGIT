<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\Brand;
use App\Entity\Vehicle;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/models')]
class ApiModelController extends AbstractController
{
    #[Route('/', name: 'app_api_model_index')]
    public function index(ModelRepository $modelRepository): Response
    {
        $models = $modelRepository->findAll();

        if (empty($models)) {
            $response = [
                "status" => "fail",
                "data" => $models,
                "message" => "Models are null or empty"
            ];
            $status = Response::HTTP_OK;
        } else {
            $response = [
                "status" => "success",
                "data" => $models,
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        }

        return new JsonResponse($response, $status);
    }

    #[Route('/{id}', name: 'app_api_model_show', methods: ['GET'])]
    public function show(?Model $model): JsonResponse
    {
        if (empty($model)) {
            $response = [
                "status" => "fail",
                "data" => $model,
                "message" => "Model not found"
            ];
            $status = Response::HTTP_NOT_FOUND;
        } else {
            $response = [
                "status" => "success",
                "data" => $model,
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        }

        return new JsonResponse($response, $status);
    }

    #[Route('/new', name: 'app_api_model_new', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if (empty($request->getContent())) {
            $response = [
                "status" => "fail",
                "data" => $request->getContent(),
                "message" => "Request data is null or empty"
            ];
            $status = Response::HTTP_BAD_REQUEST;

            return new JsonResponse($response, $status);
        }

        $model = new Model();
        $data = json_decode($request->getContent(), true);

        try {
            $model->setName($data["name"]);
            $model->setGearType($data["gearType"]);
            $model->setDescription($data["description"]);
            $model->setYear($data["year"]);


            $brand = $entityManager->getRepository(Brand::class)->find($data["brand_id"]);
            $model->setBrand($brand);

            $model->setVehicles(new ArrayCollection());

        } catch (\Exception $e) {
            $response = [
                "status" => "error",
                "data" => $model,
                "message" => $e->getMessage()
            ];
            $status = Response::HTTP_BAD_REQUEST;

            return new JsonResponse($response, $status);
        }

        $response = [
            "status" => "success",
            "data" => $model,
            "message" => ""
        ];

        $entityManager->persist($model);
        $entityManager->flush();

        $status = Response::HTTP_CREATED;

        return new JsonResponse($response, $status);
    }

    #[Route('/edit/{id}', name: 'app_api_model_edit', methods: ['PUT'])]
    public function edit(Model $model, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $model->setName($data["name"]);
            $model->setGearType($data["gearType"]);
            $model->setDescription($data["description"]);
            $model->setYear($data["year"]);

            $brand = $entityManager->getRepository(Brand::class)->find($data["brand_id"]);
            $model->setBrand($brand);

        } catch (\Exception $e) {
            $response = [
                "status" => "error",
                "data" => $model,
                "message" => $e->getMessage()
            ];
            $status = Response::HTTP_BAD_REQUEST;

            return new JsonResponse($response, $status);
        }

        $response = [
            "status" => "success",
            "data" => $model,
            "message" => "Model updated successfully"
        ];

        $entityManager->flush();

        $status = Response::HTTP_OK;

        return new JsonResponse($response, $status);
    }

    #[Route('/delete/{id}', name: 'app_api_model_delete', methods: ['DELETE'])]
    public function delete(Model $model, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($model);
        $entityManager->flush();

        $response = [
            "status" => "success",
            "data" => null,
            "message" => "Model deleted successfully"
        ];

        $status = Response::HTTP_OK;

        return new JsonResponse($response, $status);
    }
}
