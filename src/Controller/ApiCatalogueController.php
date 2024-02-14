<?php

namespace App\Controller;

use App\Repository\VehicleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/catalogues')]
class ApiCatalogueController extends AbstractController
{
    #[Route('', name: 'api_catalogue_items_index', methods: ['GET'])]
    public function index(Request $request, VehicleRepository $vehicleRepository, PaginatorInterface $paginator): JsonResponse
    {
        $q = $request->query->get('q', '');
        $page = $request->query->getInt('page', 1);

        if (empty($q)) {
            $query = $vehicleRepository->findAllQuery();
        } else {
            $query = $vehicleRepository->findByTextQuery($q);
        }

        $pagination = $paginator->paginate(
            $query,
            $page,
            16
        );

        return $this->json($pagination, Response::HTTP_OK, [], ['groups' => 'vehicle']);
    }
}
