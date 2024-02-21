<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\PurchaseFormType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/purchases')]
class PurchaseController extends AbstractController
{
    #[Route('/', name: 'app_purchase_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $purchaseForm = $this->createForm(PurchaseFormType::class);
        $purchaseForm->handleRequest($request);

        if ($purchaseForm->isSubmitted() && $purchaseForm->isValid()) {
            $formData = $purchaseForm->getData();
            $request->getSession()->set('formData', $formData);
            return $this->redirectToRoute('app_purchase_confirmation');
        }

        return $this->render('purchase/index.html.twig', [
            'purchaseForm' => $purchaseForm->createView(),
        ]);
    }

    #[Route('/confirmation', name: 'app_purchase_confirmation')]
    public function confirmation(Request $request, OrderRepository $orderRepository): Response
    {
        $formData = $request->getSession()->get('formData');

        $userId = $this->getUser();
        $pendingOrder = $orderRepository->findOneBy(['state' => 'Pendent', 'customer' => $userId]);

        $totalPrice = 0;
        foreach ($pendingOrder->getVehicles() as $vehicle) {
            $totalPrice += $vehicle->getSellPrice();
        }

        return $this->render('purchase/confirmation.html.twig', [
            'formData' => $formData,
            'totalPrice' => $totalPrice
        ]);
    }

    #[Route('/confirmation/success', name: 'app_purchase_success')]
    public function success(OrderRepository $orderRepository, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser();
        $pendingOrder = $orderRepository->findOneBy(['state' => 'Pendent', 'customer' => $userId]);

        if ($pendingOrder) {
            $customer = $pendingOrder->getCustomer();

            $totalPrice = 0;
            foreach ($pendingOrder->getVehicles() as $vehicle) {
                $totalPrice += $vehicle->getSellPrice();
            }

            $actualDate = new \DateTime();
            $randomNumber = rand(1000000, 9999999);

            $invoice = new Invoice();
            $invoice->setDate($actualDate);
            $invoice->setNumber($randomNumber);
            $invoice->setCustomer($customer);
            $invoice->setCustomerOrder($pendingOrder);
            $invoice->setPrice($totalPrice);
            $invoice->setDischarge('false');

            $pendingOrder->setState('Tancat');

            $entityManager->persist($invoice);
            $entityManager->persist($pendingOrder);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_garage_index', [], Response::HTTP_SEE_OTHER);
    }
}
