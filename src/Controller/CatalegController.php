<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/catalogue')]
class CatalegController extends AbstractController
{
    #[IsGranted("PUBLIC_ACCESS")]
    #[Route('', name: 'app_catalogue_index')]
    public function index(VehicleRepository $vehicleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $q = $request->query->get('q', '');
        if( empty($q))
            $query = $vehicleRepository->findAllQuery();
        else
            $query = $vehicleRepository->findByTextQuery($q);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            16
        );

        return $this->render('catalogue/index.html.twig', [
            'vehicles' => $pagination,
            'pagination' => $pagination,
            'q' => $q
        ]);
    }

    #[IsGranted(
        new Expression(
            'is_granted("ROLE_PRIVATE", subject) or is_granted("ROLE_PROFESSIONAL", subject)'
        ))]
    #[Route('/add/{id}', name: 'app_catalogue_add_vehicle', methods: ['GET', 'POST'])]
    public function new($id, Request $request, EntityManagerInterface $entityManager, OrderRepository $orderRepository, VehicleRepository $vehicleRepository): Response {
        if ($this->isGranted('ROLE_ADMIN')) {
            $this->addFlash(
                'warning',
                "Sols els clients poden realitzar compres"
            );
            return $this->redirectToRoute('templates');
        }

        $login = $this->getUser();
        $customer = $login->getCustomer();

        $existingOrder = $orderRepository->findOneBy(['state' => 'Pendent', 'customer' => $customer]);

        if (!$existingOrder) {
            $order = new Order();
            $order->setState('Pendent');
            $order->setCustomer($customer);

            $form = $this->createForm(OrderType::class, $order);
            $form->handleRequest($request);

            $entityManager->persist($order);
            $entityManager->flush();

            $vehicleId = $id;
            $vehicle = $vehicleRepository->find($vehicleId);
            $vehicle->setVehicleOrder($order);

            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "S'ha creat una nova ordre amb el vehicle."
            );
        } else {
            $order = $existingOrder;

            $vehicleId = $id;
            $vehicle = $vehicleRepository->find($vehicleId);

            if ($vehicle->getVehicleOrder() !== null) {
                $this->addFlash(
                    'danger',
                    'El vehicle no esta disponible.'
                );
            } else {
                $vehicle->setVehicleOrder($order);

                $entityManager->persist($vehicle);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Vehicle afegit correctament!'
                );
            }
        }

        return $this->redirectToRoute('app_catalogue_index', [], Response::HTTP_SEE_OTHER);
    }
}