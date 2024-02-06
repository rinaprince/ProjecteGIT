<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        //$vehiclesQuery = $vehicleRepository->findAllQuery();

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

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/add/{id}', name: 'app_catalogue_add_vehicle', methods: ['GET', 'POST'])]
    public function new($id, Request $request, CustomerRepository $customerRepository, EntityManagerInterface $entityManager, OrderRepository $orderRepository, VehicleRepository $vehicleRepository): Response {
        $customers = $customerRepository->findAll();
        $customer = $customers[0];

        $existingOrder = $orderRepository->findOneBy(['state' => 'Pendent', 'customer' => $customer]);

        $this->denyAccessUnlessGranted('ROLE_PRIVATE',
            null, 'Accés restringit, soles administratius');

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
        } else {
            $order = $existingOrder;

            $vehicleId = $id;
            $vehicle = $vehicleRepository->find($vehicleId);
            $vehicle->setVehicleOrder($order);

            $entityManager->persist($vehicle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_catalogue_index', [], Response::HTTP_SEE_OTHER);
    }
}

//$customerId = 2;
//$customer = $entityManager->getRepository(Customer::class)->find($customerId);