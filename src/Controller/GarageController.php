<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\InvoiceRepository;
use App\Repository\OrderRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(
    new Expression(
        'is_granted("ROLE_PRIVATE", subject) or is_granted("ROLE_PROFESSIONAL", subject)'
    ))]
#[Route('/garage')]
class GarageController extends AbstractController
{
    #[Route('', name: 'app_garage_index')]
    public function index(InvoiceRepository $invoiceRepository, OrderRepository $orderRepository): Response {
        if ($this->isGranted('ROLE_ADMIN')) {
            $this->addFlash(
                'warning',
                "Sols els clients poden realitzar compres"
            );
            return $this->redirectToRoute('templates');
        }

        $login = $this->getUser();
        $customer = $login->getCustomer();

        $pendingOrder = $orderRepository->findOneBy(['state' => 'Pendent', 'customer' => $customer]);

        if (!$pendingOrder) {
            $vehicles = [];
        } else {
            $vehicles = $pendingOrder->getVehicles()->toArray();
        }

        $closedOrders = $orderRepository->findBy(['state' => 'Tancat', 'customer' => $customer]);
        $userInvoices = $invoiceRepository->findBy(['customer' => $customer]);

        return $this->render('garage/index.html.twig', [
            'vehicles' => $vehicles,
            'invoices' => $userInvoices,
            'orders' => $closedOrders
        ]);
    }


    #[Route('/delete/{id}', name: 'app_garage_delete_vehicle')]
    public function remove($id, VehicleRepository $vehicleRepository, EntityManagerInterface $entityManager): Response {
        $vehicleId = $id;
        $vehicle = $vehicleRepository->find($vehicleId);
        $vehicle->setVehicleOrder(null);

        $entityManager->persist($vehicle);
        $entityManager->flush();

        return $this->redirectToRoute('app_garage_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/close', name: 'app_garage_close_order')]
    public function close(OrderRepository $orderRepository, EntityManagerInterface $entityManager, InvoiceRepository $invoiceRepository): Response {
        $userId = $this->getUser();
        $pendingOrder = $orderRepository->findOneBy(['state' => 'Pendent', 'customer' => $userId]);

//        if ($pendingOrder) {
//            $customer = $pendingOrder->getCustomer();
//
//            $totalPrice = 0;
//            foreach ($pendingOrder->getVehicles() as $vehicle) {
//                $totalPrice += $vehicle->getSellPrice();
//            }
//
//            $actualDate = new \DateTime();
//
//            $invoice = new Invoice();
//            $invoice->setDate($actualDate);
//            $invoice->setNumber(2);
//            $invoice->setCustomer($customer);
//            $invoice->setCustomerOrder($pendingOrder);
//            $invoice->setPrice($totalPrice);
//
//            $pendingOrder->setState('Tancat');
//
//            $entityManager->persist($invoice);
//            $entityManager->persist($pendingOrder);
//            $entityManager->flush();
//        }

        return $this->redirectToRoute('app_purchase_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/cancel', name: 'app_garage_cancel_order')]
    public function cancel(VehicleRepository $vehicleRepository, OrderRepository $orderRepository, EntityManagerInterface $entityManager): Response {
        $userId = $this->getUser();
        $pendingOrder = $orderRepository->findOneBy(['state' => 'Pendent', 'customer' => $userId]);

        if ($pendingOrder) {
            $vehicles = $vehicleRepository->findBy(['vehicleOrder' => $pendingOrder]);

            foreach ($vehicles as $vehicle) {
                $vehicle->setVehicleOrder(null);
                $entityManager->persist($vehicle);
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('app_garage_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/details', name: 'app_order_details', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('order/details.html.twig', [
            'order' => $order,
        ]);
    }
}