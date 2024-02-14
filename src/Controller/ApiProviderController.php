<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Repository\ProviderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[IsGranted('ROLE_ADMINISTRATIVE', message: 'Accés restringit, soles administratius')]
#[Route('/api/v1/providers')]
class ApiProviderController extends AbstractController
{

    #[Route('', name: 'app_api_provider')]
    public function index(ProviderRepository $providerRepository): JsonResponse
    {
        $data = $providerRepository->findAll();

        if ($data == null || $data == "") {
            $providersJson = [
                "status" => "fail",
                "data" => ["provider" => $data],
                "message" => "No se pudo acceder a los datos de provedores"
            ];
        } else {
            $providersJson = [
                "status" => "success",
                "data" => ["providers" => $data],
                "message" => null
            ];
        }

        return new JsonResponse($providersJson, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_api_provider_show')]
    public function show(?Provider $provider): JsonResponse
    {
        $data = $provider;

        if (!empty($data)) {
            $providersJson = [
                "status" => "success",
                "data" => ["provider" => $data],
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        } else {
            $providersJson = [
                "status" => "error",
                "data" => ["provider" => $data],
                "message" => "No se encontro ningun proveedor"
            ];
            $status = Response::HTTP_NOT_FOUND;
        }
        return new JsonResponse($providersJson, $status);
    }

    #[Route('/new', name: 'app_api_provider_new')]
    public function create(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $provider = new Provider();
        $data = $request->toArray();

        try {
            $provider->setEmail($data["email"]);
            $provider->setBusinessName($data["businessName"]);
            $provider->setPhone($data["phone"]);
            $provider->setDni($data["dni"]);
            $provider->setCif($data["cif"]);
            $provider->setAddress($data["address"]);
            $provider->setBankTitle("fichero.pdf");
            $provider->setManagerNif($data["managerNif"]);
            $provider->setLOPDdoc("lopd.pdf");
            $provider->setConstitutionArticle("Constitucion.doc");

            $errors = $validator->validate($provider);
            if (count($errors) > 0) {
                $providerJson = [
                    "status" => "error",
                    "data" => ["provider" => $data],
                    "message" => "Los campos no tienen el formato correcto"
                ];
                $status = Response::HTTP_BAD_REQUEST;
            }
            else{
                $providerJson = [
                    "status" => "success",
                    "data" => ["provider" => $data],
                    "message" => ""
                ];
                $status = Response::HTTP_CREATED;

                $entityManager->persist($provider);
                $entityManager->flush();
            }

        } catch (\Exception $e) {
            $providerJson = [
                "status" => "error",
                "data" => ["provider" => $data],
                "message" => $e
            ];
            $status = Response::HTTP_BAD_REQUEST;
            return new JsonResponse($providerJson, $status);
        }



        return new JsonResponse($providerJson, $status);
    }
}