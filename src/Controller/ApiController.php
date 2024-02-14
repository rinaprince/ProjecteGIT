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
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/username-validation', name: 'app_api_username_validation', methods: ['POST'])]
    public function usernameValidation(Request $request, LoginRepository $loginRepository): Response
    {
        $requestData = json_decode($request->getContent(), true);
        $username = $requestData['username'];

        $usernameExists = $loginRepository->createQueryBuilder('l')
            ->select('l.username')
            ->where("l.username = :username")
            ->setParameter("username", $username)
            ->getQuery()
            ->getOneOrNullResult();

        $responseData = ['username_exists' => (bool) $usernameExists];
        return new JsonResponse($responseData);
    }


    #[IsGranted(
        new Expression(
            'is_granted("ROLE_PRIVATE", subject) or is_granted("ROLE_PROFESSIONAL", subject)'
        ))]

    #[Route('/add/{id}', name: 'app_api_pending_orders', methods: ['POST'])]
    public function new($id, Request $request, CustomerRepository $customerRepository, EntityManagerInterface $entityManager, OrderRepository $orderRepository, VehicleRepository $vehicleRepository): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $customer = $customerRepository->find($userId);

        $existingOrder = $orderRepository->findOneBy(['state' => 'Pendent', 'customer' => $customer]);

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

    #[Route('/models', name: 'app_api_model')]
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

