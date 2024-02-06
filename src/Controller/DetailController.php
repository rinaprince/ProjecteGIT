<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Repository\ImageRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/details')]
class DetailController extends AbstractController
{
    #[Route('/{id}', name: 'app_detail')]
    public function index(Vehicle $vehicle): Response
    {
        return $this->render('detail/index.html.twig', [
            "vehicle"=>$vehicle,
        ]);
    }
}
