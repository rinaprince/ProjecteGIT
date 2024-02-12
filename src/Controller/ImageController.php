<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Vehicle;
use App\Form\ImageType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    #[Route('/images', name: 'app_image')]
    public function index(): Response
    {
        return $this->render('image/index.html.twig', [
            'controller_name' => 'ImageController',
        ]);
    }

    #[Route('/vehicles/{id}/images/add', name: 'app_image_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, VehicleRepository $vehicleRepository, $id): Response
    {

        // Obtindre el vehicle utilitzant l'ID
        $vehicle = $vehicleRepository->find($id);

        // Comprovar si el vehicle existeix
        if (!$vehicle) {
            throw $this->createNotFoundException('El vehicle no existeix');
        }

        $image = new Image();
        $image->setVehicle($vehicle);

        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($image);
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('image/new.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }
}
