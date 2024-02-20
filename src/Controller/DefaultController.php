<?php

namespace App\Controller;

use App\Repository\BrandRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_front_office')]
    public function index(BrandRepository $brandRepository): Response
    {
        $brands = $brandRepository->findBy([], ['name' => 'ASC']);

        return $this->render('front_office/index_front.html.twig', [
            'brands' => $brands
        ]);
    }

    #[Route('/backoffice', name: 'app_back_office')]
    public function back(VehicleRepository $vehicleRepository): Response
    {
        // Obtindre tots els vehicles
        $vehicles = $vehicleRepository->findAll();

        // Seleccionar 5 vehicles aleatoriament
        shuffle($vehicles);
        $randomVehicles = array_slice($vehicles, 0, 5);

        // Contador per a contar les ventes de cada marca
        $brandSalesCount = [];

        // Contar les ventes
        foreach ($vehicles as $vehicle) {
            $brand = $vehicle->getModel()->getBrand()->getName();
            if (!isset($brandSalesCount[$brand])) {
                $brandSalesCount[$brand] = 0;
            }
            $brandSalesCount[$brand]++;
        }

        $totalSales = count($vehicles);

        // Calcular el percentatge de ventes per a cada marca
        $brandSalesPercentage = [];
        foreach ($brandSalesCount as $brand => $count) {
            $brandSalesPercentage[$brand] = ($count / $totalSales) * 100;
        }

        $topBrands = array_slice($brandSalesPercentage, 0, 5);

        return $this->render('back_office/index_back.html.twig', [
            'vehicles' => $randomVehicles,
            'topBrands' => $topBrands
        ]);
    }
}
