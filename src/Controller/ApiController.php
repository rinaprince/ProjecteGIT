<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1')]
class ApiController extends AbstractController
{
    #[Route('/count', name: 'app_api_count')]
    public function index(OrderRepository $orderRepository): Response
    {
        $pendingOrder = $orderRepository->findOneBy(['state' => 'Pendent']);
        $totalVehicles = 0;

        if ($pendingOrder) {
            $totalVehicles = count($pendingOrder->getVehicles());
        }

        //  [
        //      "status" => "success",
        //      "data" => ["count_number" => $totalVehicles]
        //      "message" => null
        // ]

        $response = $this->json(['count_number' => $totalVehicles]);
        return $response;
    }
}
