<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/purchases')]
class PurchaseController extends AbstractController
{
    #[Route('/', name: 'app_purchase_index')]
    public function index(): Response
    {
        return $this->render('purchase/index.html.twig', [
            'controller_name' => 'PurchaseController',
        ]);
    }

    #[Route('/confirmation', name: 'app_purchase_confirmation')]
    public function confirmation(OrderRepository $orderRepository, EntityManagerInterface $entityManager): Response {
        $userId = $this->getUser();
        $pendingOrder = $orderRepository->findOneBy(['state' => 'Pendent', 'customer' => $userId]);

        if ($pendingOrder) {
            $customer = $pendingOrder->getCustomer();

            $totalPrice = 0;
            foreach ($pendingOrder->getVehicles() as $vehicle) {
                $totalPrice += $vehicle->getSellPrice();
            }

            $actualDate = new \DateTime();

            $invoice = new Invoice();
            $invoice->setDate($actualDate);
            $invoice->setNumber(2);
            $invoice->setCustomer($customer);
            $invoice->setCustomerOrder($pendingOrder);
            $invoice->setPrice($totalPrice);

            $pendingOrder->setState('Tancat');

            $entityManager->persist($invoice);
            $entityManager->persist($pendingOrder);
            $entityManager->flush();
        }

        return $this->render('purchase/confirmation.html.twig', [
            'controller_name' => 'PurchaseController',
        ]);
    }


}
