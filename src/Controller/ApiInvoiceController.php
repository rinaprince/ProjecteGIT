<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\Order;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/invoices')]
class ApiInvoiceController extends AbstractController
{
    #[Route('/', name: 'app_api_invoice_index')]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        $invoices = $invoiceRepository->findAll();

        if (empty($invoices)) {
            $invoicesJson = [
              "status" => "fail",
              "data" => $invoices,
              "message" => "Invoices in null or empty"
            ];
            $status = Response::HTTP_OK;
        } else {
            $invoicesJson = [
                "status" => "success",
                "data" => $invoices,
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        }

        return new JsonResponse ($invoicesJson, $status);
    }

    #[Route('/{id}', name: 'app_api_invoice_show', methods: ['GET'])]
    public function show(?Invoice $invoice): JsonResponse
    {
        if(empty($invoice)){
            $invoiceJson = [
                "status" => "fail",
                "data" => $invoice,
                "message" => "pepe"
            ];
            $status = Response::HTTP_NOT_FOUND;
        }
        else{
            $invoiceJson = [
                "status" => "success",
                "data" => $invoice,
                "message" => ""
            ];
            $status = Response::HTTP_OK;
        }
        return new JsonResponse($invoiceJson, $status);
    }

    #[Route('/new', name: 'app_api_invoice_new', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, CustomerRepository $customerRepository, OrderRepository $orderRepository): JsonResponse
    {
        if (empty($request->getContent())) {
            $invoicesJson = [
                "status" => "fail",
                "data" => $request->getContent(),
                "message" => "Request data is null or empty"
            ];
            $status = Response::HTTP_BAD_REQUEST;
            return new JsonResponse($invoicesJson, $status);

        } else {
            $invoice = new Invoice();
            $data = [];
            if ($content = $request->getContent()) {
                $data = json_decode($content, true);
            }

            try {
                $invoice->setNumber($data["number"]);
                $invoice->setPrice($data["price"]);
                $invoice->setDate($data["date"]);
                $customer = $customerRepository->find($data["customer_id"]);
                $order = $orderRepository->find($data["order_id"]);

                $invoice->setCustomer($customer);
                $invoice->setCustomerOrder($order);

            } catch (\Exception $e) {
                $invoicesJson = [
                    "status" => "error",
                    "data" => $invoice,
                    "message" => $e
                ];
                $status = Response::HTTP_BAD_REQUEST;
                return new JsonResponse($invoicesJson, $status);
            }
            $invoicesJson = [
                "status" => "success",
                "data" => $invoice,
                "message" => ""
            ];

            $entityManager->persist($invoice);
            $entityManager->flush();

            $status = Response::HTTP_CREATED;
        }

        return new JsonResponse($invoicesJson, $status);
    }
}