<?php

namespace App\Controller;

use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalegController extends AbstractController
{
    #[Route('/cataleg', name: 'app_cataleg')]
    public function index(VehicleRepository $vehicleRepository): Response
    {
        $vehicles = $vehicleRepository ->findAll();
        return $this->render('cataleg/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }
}
