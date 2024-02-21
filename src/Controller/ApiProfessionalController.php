<?php

namespace App\Controller;


use App\Entity\Login;
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

    #[Route('', name: 'api_professional_index', methods: ['GET'])]
    public function index(ProfessionalRepository $professionalRepository): JsonResponse
    {
        $professionals = $professionalRepository->findAll();

        if(empty($professionals)){
            $professionalsJson = [
                "status" => "fail",
                "data" => $professionals,
                "message" => "professionals is null or empty"
            ];
            $status = Response::HTTP_OK;
        }
        else{
            $professionalsJson = [
                "status" => "success",
                "data" => $professionals,
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        }

        return new JsonResponse($professionalsJson, $status);
    }

    #[Route('/{id}', name: 'api_professional_show', methods: ['GET'])]
    public function show(Request $request,  ?Professional $professional): JsonResponse
    {
        if(empty($professional)){
            $professionalsJson = [
                "status" => "fail",
                "data" => $professional,
                "message" => "professional is null or empty"
            ];
            $status = Response::HTTP_NOT_FOUND;
        }
        else{
            $professionalsJson = [
                "status" => "success",
                "data" => $professional,
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        }
        return new JsonResponse($professionalsJson, $status);
    }
    #[Route('', name: 'api_professional_new', methods: ['POST'])]
    public function create(Request $request,EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {

        if(empty($request->getContent())){
            $professionalsJson = [
                "status" => "fail",
                "data" => $request->getContent(),
                "message" => "request data is null or empty"
            ];
            $status = Response::HTTP_BAD_REQUEST;
        }
        else{
            $professional = new Professional();
            $data = [];
            if ($content = $request->getContent()) {
                $data = json_decode($content, true);
            }

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
                $login = new Login();
                $professional->setLogin($login);
                $professional->getLogin()->setUsername($data["username"]);
                $professional->getLogin()->setPassword($data["password"]);
                $professional->getLogin()->setRole('ROLE_PROFESSIONAL');

                $violations = $validator->validate($professional);
                if (count($violations) > 0) {
                    $professionalsJson = [
                        "status" => "error",
                        "data" => $violations,
                        "message" => ''
                    ];
                    $status = Response::HTTP_BAD_REQUEST;
                    return new JsonResponse($professionalsJson, $status);
                }

            } catch (\Exception $e) {
                $professionalsJson = [
                    "status" => "error",
                    "data" => $professional,
                    "message" => $e
                ];
                $status = Response::HTTP_BAD_REQUEST;
                return new JsonResponse($professionalsJson, $status);
            }
            $professionalsJson = [
                "status" => "success",
                "data" => $professional,
                "message" => ""
            ];

            $em->persist($professional);
            $em->flush();

            $status = Response::HTTP_CREATED;
        }

        return new JsonResponse($professionalsJson, $status);
    }

    #[Route('/{id}', name: 'api_professional_delete', methods: ['DELETE'])]
    public function delete(Request $request ,?Professional $professional, EntityManagerInterface $entityManager): JsonResponse
    {
        if(empty($professional)){
            $professionalsJson = [
                "status" => "fail",
                "data" => $professional,
                "message" => "professional is null or empty"
            ];
            $status = Response::HTTP_NOT_FOUND;
        }
        else{
            $professionalsJson = [
                "status" => "success",
                "data" => $professional,
                "message" => ""
            ];
            $status = Response::HTTP_OK;
            $entityManager->remove($professional);
            $entityManager->flush();
        }

        return new JsonResponse($professionalsJson, $status);
    }

}