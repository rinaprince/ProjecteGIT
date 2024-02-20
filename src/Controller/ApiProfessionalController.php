<?php

namespace App\Controller;

use App\Entity\Professional;
use App\Repository\LoginRepository;
use App\Repository\ProfessionalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api/v1/professionals')]
class ApiProfessionalController extends AbstractController
{
    #[Route('', name: 'app_api_professional')]
    public function index(ProfessionalRepository $professionalRepository): JsonResponse
    {
        $data = $professionalRepository->findAll();

        if ($data == null || $data == "") {
            $professionalsJson = [
                "status" => "fail",
                "data" => ["professionals" => $data],
                "message" => "No es pot accedir a les dades dels professionals"
            ];
        } else {
            $professionalsJson = [
                "status" => "success",
                "data" => ["professionals" => $data],
                "message" => null
            ];
        }

        return new JsonResponse($professionalsJson, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_api_professional_show')]
    public function show(?Professional $professional): JsonResponse
    {
        $data = $professional;

        if (!empty($data)) {
            $professionalsJson = [
                "status" => "success",
                "data" => ["professional" => $data],
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        } else {
            $professionalsJson = [
                "status" => "error",
                "data" => ["professional" => $data],
                "message" => "No s'ha trobat ningun professional"
            ];
            $status = Response::HTTP_NOT_FOUND;
        }
        return new JsonResponse($professionalsJson, $status);
    }

    #[Route('/new', name: 'app_api_professional_new')]
    public function create(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $professional = new Professional();
        $data = $request->toArray();

        try {
            $professional->setName($data["name"]);
            $professional->setLastname($data["lastname"]);
            $professional->setAddress($data["address"]);
            $professional->setDni($data["dni"]);
            $professional->setPhone($data["phone"]);
            $professional->setEmail($data["email"]);
            $professional->setCif($data["cif"]);
            $professional->setManagerNif($data["nif"]);
            $professional->setLOPDdoc("docLOPD.pdf");
            $professional->setBussinessName($data["bussinessName"]);
            $professional->setConstitutionWriting($data["constitutionWriting"]);
            $professional->setSubscription($data["subscription"]);


            $errors = $validator->validate($professional);
            if (count($errors) > 0) {
                $professionalJson = [
                    "status" => "error",
                    "data" => ["professional" => $data],
                    "message" => "Els camps no tenen el format correcte"
                ];
                $status = Response::HTTP_BAD_REQUEST;
            } else {
                $professionalJson = [
                    "status" => "success",
                    "data" => ["professional" => $data],
                    "message" => ""
                ];
                $status = Response::HTTP_CREATED;

                $entityManager->persist($professional);
                $entityManager->flush();
            }

        } catch (\Exception $e) {
            $professionalJson = [
                "status" => "error",
                "data" => ["professional" => $data],
                "message" => $e
            ];
            $status = Response::HTTP_BAD_REQUEST;
            return new JsonResponse($professionalJson, $status);
        }


        return new JsonResponse($professionalJson, $status);
    }
}
