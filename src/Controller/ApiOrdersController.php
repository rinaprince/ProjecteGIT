<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/orders')]
class ApiOrdersController extends AbstractController
{
    private $orderRepository;
    private $entityManager;

    public function __construct(OrderRepository $orderRepository, EntityManagerInterface $entityManager)
    {
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'api_order_index',methods: ['GET'])]
    public function index(Request $request, OrderRepository $orderRepository): JsonResponse
    {
        $orders = $orderRepository->findAll();
        if(empty($orders)){
            $response = [
                "status" => "fail",
                "data" => $orders,
                "message" => "Orders is null or empty"
            ];
            $status = Response::HTTP_OK;
        }
        else{
            $response = [
                "status" => "success",
                "data" => $orders,
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        }
        return new JsonResponse($response, $status);
    }

    #[Route('/{id}', name: 'api_v1_order_show', methods: ['GET'])]
    public function show(Order $order): JsonResponse
    {
        $response = [
            "status" => "success",
            "data" => $order,
            "message" => ""
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    #[Route('/new', name: 'api_v1_order_new', methods: ['POST'])]
    public function create(Request $request, CustomerRepository $customerRepository): JsonResponse
    {
        if (empty($request->getContent())) {
            $response = [
                "status" => "fail",
                "data" => $request->getContent(),
                "message" => "Request data is null or empty"
            ];
            $status = Response::HTTP_BAD_REQUEST;
            return new JsonResponse($response, $status);
        } else {
            $data = json_decode($request->getContent(), true);

            $order = new Order();

            try {
                $order->setState($data['state']);
                $customer = $customerRepository->find($data["customer_id"]);
                $order->setCustomer($customer);

                $this->entityManager->persist($order);
                $this->entityManager->flush();

                $response = [
                    "status" => "success",
                    "data" => $order,
                    "message" => "Order created successfully"
                ];

                $status = Response::HTTP_CREATED;
            } catch (\Exception $e) {
                $response = [
                    "status" => "error",
                    "data" => null,
                    "message" => $e->getMessage()
                ];
                $status = Response::HTTP_BAD_REQUEST;
            }
        }

        return new JsonResponse($response, $status);
    }

    #[Route('/{id}', name: 'api_v1_order_update', methods: ['GET'])]
    public function update(Request $request, Order $order): JsonResponse
    {
        // Extract data from request
        $data = json_decode($request->getContent(), true);

        $order->setState($data['state']);

        $this->entityManager->flush();

        $response = [
            "status" => "success",
            "data" => $order,
            "message" => "Order updated successfully"
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    #[Route('/{id}/delete', name: 'api_v1_order_delete', methods: ['GET'])]
    public function delete(Order $order): JsonResponse
    {
        if ($order->getInvoice()==null) {
            $response = [
                "status" => "Error",
                "message" => "Order has an invoice"
            ];
            return new JsonResponse($response, Response::HTTP_INTERNAL_SERVER_ERROR);

        }


        $this->entityManager->remove($order);
        $this->entityManager->flush();


        $response = [
            "status" => "success",
            "message" => "Order deleted successfully"
        ];
        return new JsonResponse($response, Response::HTTP_OK);
    }
}

