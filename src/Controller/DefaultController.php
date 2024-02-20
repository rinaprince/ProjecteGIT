<?php

namespace App\Controller;

use App\Repository\BrandRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_front_office')]
    public function index(BrandRepository $brandRepository, OrderRepository $orderRepository): Response
    {
        $brands = $brandRepository->findBy([], ['name' => 'ASC']);
        // Seleccionar 5 vehicles aleatoriament
        shuffle($brands);
        $randomBrands = array_slice($brands, 0, 28);

        return $this->render('front_office/index_front.html.twig', [
            'brands' => $randomBrands
        ]);
    }

    #[Route('/backoffice', name: 'app_back_office')]
    public function back(): Response{
        return $this->render('back_office/index_back.html.twig',
            ['controller_name' => 'BackController']);
    }
}
