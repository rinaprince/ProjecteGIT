<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\CustomerRepository;
use App\Repository\LoginRepository;
use App\Repository\OrderRepository;
use App\Repository\ProfessionalRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1')]
class ApiProfessionalController extends AbstractController
{
    public function index(Request $request, ProfessionalRepository $professionalRepository): JsonResponse
    {
        $movies = $movieRepository->findAll();

        return new JsonResponse($movies, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_moives_show", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request,  ?Movie $movie): JsonResponse
    {

        if (!empty($movie))
            return new JsonResponse($movie, Response::HTTP_OK);

        else
            return new JsonResponse("error", Response::HTTP_NOT_FOUND);
    }

    /**
     *
     * @Route("/", name="api_movies_create", methods={"POST"})
     */

    public function create(Request $request): JsonResponse
    {
        $movie = new Movie();
        $data = [];
        if ($content = $request->getContent()) {
            $data = json_decode($content, true);
        }

        try {
            $movie->setTitle($data["title"]);
            $movie->setOverview($data["overview"]);
            $movie->setTagline($data["tagline"]);
            $movie->setPoster($data["poster"]);
            $movie->setReleaseDate(new \DateTime($data["release_date"]));

        } catch (\Exception $e) {
            $error["code"] = $e->getCode();
            $error["message"] = $e->getMessage();
            return new JsonResponse($error, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($movie);
        $em->flush();

        return new JsonResponse($movie, Response::HTTP_CREATED);
    }
}
