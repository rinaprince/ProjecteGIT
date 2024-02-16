<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[IsGranted('ROLE_ADMINISTRATIVE', message: 'Accés restringit, soles administratius')]
#[Route('/api/v1/vehicles')]
class ApiVehiclesController extends AbstractController
{
    #[Route('', name: 'app_api_vehicle', methods: ['GET'])]
    public function index(VehicleRepository $vehicleRepository): JsonResponse
    {
        $data = $vehicleRepository->findAll();

        if ($data == null || $data == "") {
            $vehiclesJson = [
                "status" => "fail",
                "data" => ["vehicle" => $data],
                "message" => "No es pot accedir a les dades dels vehicles"
            ];
        } else {
            $vehiclesJson = [
                "status" => "success",
                "data" => ["vehicles" => $data],
                "message" => null
            ];
        }

        return new JsonResponse($vehiclesJson, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_api_vehicle_show', methods: ['GET'])]
    public function show(?Vehicle $vehicle): JsonResponse
    {
        $data = $vehicle;

        if (!empty($data)) {
            $vehiclesJson = [
                "status" => "success",
                "data" => ["vehicle" => $data],
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        } else {
            $vehiclesJson = [
                "status" => "error",
                "data" => ["vehicle" => $data],
                "message" => "No s'ha trobat cap vehicle"
            ];
            $status = Response::HTTP_NOT_FOUND;
        }
        return new JsonResponse($vehiclesJson, $status);
    }

    #[Route('/new', name: 'app_api_vehicle_new', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $vehicle = new Vehicle();
        $data = $request->toArray();

        try {
            $vehicle->setPlate($data['plate']);
            $vehicle->setObservedDamages($data['observedDamages']);
            $vehicle->setKilometers($data['kilometers']);
            $vehicle->setBuyPrice($data['buyPrice']);
            $vehicle->setSellPrice($data['sellPrice']);
            $vehicle->setFuel($data['fuel']);
            $vehicle->setIva($data['iva']);
            $vehicle->setDescription($data['description']);
            $vehicle->setChassisNumber($data['chassisNumber']);
            $vehicle->setGearShit($data['gearShit']);
            $vehicle->setIsNew($data['isNew']);
            $vehicle->setTransportIncluded($data['transportIncluded']);
            $vehicle->setColor($data['color']);
            $vehicle->setRegistrationDate($data['registrationDate']);
            $vehicle->setModel($data['model']);

            $errors = $validator->validate($vehicle);
            if (count($errors) > 0) {
                $vehiclesJson = [
                    "status" => "error",
                    "data" => ["vehicle" => $data],
                    "message" => "Els camps no tenen el format correcte"
                ];
                $status = Response::HTTP_BAD_REQUEST;
            }
            else{
                $vehiclesJson = [
                    "status" => "success",
                    "data" => ["vehicle" => $data],
                    "message" => ""
                ];
                $status = Response::HTTP_CREATED;

                $entityManager->persist($vehicle);
                $entityManager->flush();
            }

        } catch (\Exception $e) {
            $vehiclesJson = [
                "status" => "error",
                "data" => ["vehicle" => $data],
                "message" => $e
            ];
            $status = Response::HTTP_BAD_REQUEST;
            return new JsonResponse($vehiclesJson, $status);
        }



        return new JsonResponse($vehiclesJson, $status);
    }
}