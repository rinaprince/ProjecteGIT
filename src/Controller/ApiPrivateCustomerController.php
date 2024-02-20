<?php

namespace App\Controller;

use App\Entity\PrivateCustomer;
use App\Repository\PrivateCustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api/v1/private/customers')]
class ApiPrivateCustomerController extends AbstractController
{
    #[Route('', name: 'app_api_private_customers')]
    public function index(PrivateCustomerRepository $privateCustomerRepository): JsonResponse
    {
        $private = $privateCustomerRepository->findAll();
        if($private == "" || $private == null){
            $privateJson = [
                "status" => "fail",
                "data" => $private,
                "message" => "Private no té contingut"

            ];
        }
        else{
            $privateJson = [
               "status" => "success",

                "data" => $private,
                "message" => null
            ];
        }

        return new JsonResponse($privateJson, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_api_private_customers_show')]
    public function show(?PrivateCustomer $privateCustomer): JsonResponse
    {
        if(!empty($privateCustomer)){
            $privateJson = [
                "status" => "succes",
                "data" => $privateCustomer,
                "message" => null
            ];
            $status = Response::HTTP_OK;
        }
        else {
            $privateJson = [
                "status" => "error",
                "data" => $privateCustomer,
                "message" => "No s'ha pogut trobar l'usuari"
            ];
            $status = Response::HTTP_NOT_FOUND;
        }
        return new JsonResponse($privateJson, $status);
    }

    #[Route('/new', name: 'app_api_private_customers_new')]
    public function create(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $privateCustomer = new PrivateCustomer();
        $data = $request->toArray();
        try {
            $privateCustomer->setName($data["name"]);
            $privateCustomer->setLastname($data["lastname"]);
            $privateCustomer->setDni($data["dni"]);
            $privateCustomer->setEmail($data["email"]);
            $privateCustomer->setAddress($data["address"]);
            $privateCustomer->setPhone($data["phone"]);

            $errors = $validator->validate($privateCustomer);
            if (count($errors) > 0) {
                $privateCustomerJson = [
                    "status" => "error",
                    "data" => ["privateCustomer" => $data],
                    "message" => "El format dels camps no és correcte"
                ];
                $status = Response::HTTP_BAD_REQUEST;

            }else{
                $privateCustomerJson = [
                    "status" => "error",
                    "data" => ["privateCustomer" => $data],
                    "message" => ""
                ];
                $status = Response::HTTP_CREATED;

                $entityManager->persist($privateCustomer);
                $entityManager->flush();
            }
        } catch (\Exception $e) {
            $privateCustomerJson = [
                "status" => "error",
                "data" => ["provider" => $data],
                "message" => $e
            ];
            $status = Response::HTTP_BAD_REQUEST;
            return new JsonResponse($privateCustomerJson, $status);
        }
        return new JsonResponse($privateCustomerJson, $status);
    }
}