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

#[Route('api/v1/private/customers')]
class ApiPrivateCustomerController extends AbstractController
{
    #[Route('', name: 'app_api_private_customer')]
    public function index(PrivateCustomerRepository $privateCustomerRepository) :JsonResponse
    {
        $private = $privateCustomerRepository->findAll();
        if($private == "" || $private == null){
            $privateJson = [
                "status" => "fail",
                "data" => $private,
                "message" => "Private no te contingut"
            ];
        }
        else{
            $privateJson = [
                "status" => "succes",
                "data" => $private,
                "message" => null
            ];
        }

        return new JsonResponse($privateJson, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_api_private_customer_show')]
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
                "message" => "no se ha podido encontrar el usuari"
            ];
            $status = Response::HTTP_NOT_FOUND;
        }
        return new JsonResponse($privateJson, $status);
    }

    #[Route('/new', name: 'app_api_private_customer_new')]
    public function create(Request $request): JsonResponse
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
        }catch (\Exception $e){
            $error["code"] = $e->getCode();
            $error["message"] = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST);
        }
        $status = Response::HTTP_BAD_REQUEST;
        return new JsonResponse($privateCustomer, $status);
    }
}