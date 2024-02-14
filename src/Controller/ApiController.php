<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\CustomerRepository;
use App\Repository\LoginRepository;
use App\Repository\ModelRepository;
use App\Repository\OrderRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1')]
class ApiController extends AbstractController
{
    #[Route('/count', name: 'app_api_count')]
    public function index(OrderRepository $orderRepository): Response
    {
        //  [
//      "status" => "success",
//      "data" => ["count_number" => $totalVehicles]
//       message" => null
        //  ]

        $userId = $this->getUser();

        $pendingOrder = $orderRepository->findOneBy(['state' => 'Pendent', 'customer' => $userId]);

        $totalVehicles = 0;

        if ($pendingOrder) {
            $totalVehicles = count($pendingOrder->getVehicles());
        }

        $response = $this->json(['count_number' => $totalVehicles]);

        return $response;
    }

    #[Route('/username_validation', name: 'app_api_username_validation')]
    public function usernameValidation(LoginRepository $loginRepository): Response
    {
        $query = $loginRepository->findAll();

        return "hola";
    }


    #[Route('/add/{id}', name: 'app_api_pending_orders', methods: ['POST'])]
    public function new($id, Request $request, CustomerRepository $customerRepository, EntityManagerInterface $entityManager, OrderRepository $orderRepository, VehicleRepository $vehicleRepository): JsonResponse
    {

        $userId = $this->getUser()->getId();
        $customer = $customerRepository->find($userId);


        $existingOrder = $orderRepository->findOneBy(['state' => 'Pendent', 'customer' => $customer]);


        $this->denyAccessUnlessGranted('ROLE_PRIVATE', null, 'Accés restringit');


        if (!$existingOrder) {
            $order = new Order();
            $order->setState('Pendent');
            $order->setCustomer($customer);


            $entityManager->persist($order);
            $entityManager->flush();


            $vehicle = $vehicleRepository->find($id);
            $vehicle->setVehicleOrder($order);


            $entityManager->persist($vehicle);
            $entityManager->flush();
        } else {

            $order = $existingOrder;


            $vehicle = $vehicleRepository->find($id);
            $vehicle->setVehicleOrder($order);


            $entityManager->persist($vehicle);
            $entityManager->flush();
        }


        return new JsonResponse(['success' => true]);
    }
    #[Route('/models', name: 'app_api_models')]
    public function model(ModelRepository $modelRepository): Response
    {
        $models = $modelRepository->findAll();

        $modelNames = [];

        foreach ($models as $model) {
            $modelNames[] = $model->getFullname();
        }

        $response = $this->json(["data" => ["models" => $modelNames]]);
        return $response;
    }
}
