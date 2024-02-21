<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/invoices')]
class InvoiceController extends AbstractController
{
    #[IsGranted('ROLE_ADMINISTRATIVE', message: 'Accés restringit, soles administratius')]
    #[Route('', name: 'app_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository, PaginatorInterface $paginator, Request $request): Response    {

       // $user = $this->getUser();

        $q = $request->query->get('q', '');
        if( empty($q))
            $query = $invoiceRepository->findAllQuery();
        else
            $query = $invoiceRepository->findByTextQuery($q);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            16
        );
    
    //    $arrayInvoices = $invoiceRepository->findInvoicesForLoggedInUser($user);
        $invoicesQuery = $invoiceRepository->findAllActive();

        $paginatedInvoices = $paginator->paginate(
            $invoicesQuery,
            $request->query->getInt('page', 1),
            5
        );
    
/*        $config = [
            "number" => "Numero",
            "customer.name" => "Client",
            "price" => "Precio",
            "date" => "Fecha",
        ];*/

        return $this->render('invoice/index.html.twig', [
            'paginatedInvoices' => $paginatedInvoices,
            'q' => $q
        ]);
    }

    #[IsGranted('ROLE_ADMINISTRATIVE', message: 'Accés restringit, soles administratius')]
    #[Route('/new', name: 'app_invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[IsGranted('ROLE_ADMINISTRATIVE', message: 'Accés restringit, soles administratius')]
    #[Route('/{id}/edit', name: 'app_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {

            $form = $this->createForm(InvoiceType::class, $invoice);
            $form->handleRequest($request);

        dump($invoice);
       if ($this->isCsrfTokenValid('edit'.$invoice->getId(), $request->request->get('_token'))) {
                $entityManager->flush();

                return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
       }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_invoice_delete', methods: ['POST', 'GET'])]
    #[IsGranted('ROLE_ADMINISTRATIVE')]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        try {
            // Marcar la factura como eliminada (soft delete)
            $invoice->setDischarge(true);
            $entityManager->persist($invoice);
            $entityManager->flush();

            // Redirigir a la página de índice de facturas
            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        } catch (\Exception $e){
            throw new BadRequestHttpException('Error al eliminar la factura: ' . $e->getMessage());
        }
    }

    #[IsGranted('ROLE_PRIVATE', message: 'Accés restringit')]
    #[Route('/myinvoices', name: 'app_invoice_myinvoices', priority: 2)]
    public function myinvoices(InvoiceRepository $invoiceRepository): Response    
    {
        $customer = $this->getUser()->getCustomer();
    
        $arrayInvoices = $invoiceRepository->findBy(['customer' => $customer]);
    
        return $this->render('invoice/myinvoices.html.twig', [
            'invoices'   => $arrayInvoices
        ]);
    }

    #[IsGranted('ROLE_PRIVATE', message: 'Accés restringit')]
    #[Route('/myinvoices/{id}', name: 'app_invoice_myinvoices_detail', methods: ['GET'])]
    public function detail(Invoice $invoice): Response
    {
        return $this->render('invoice/detail.html.twig', [
            'invoice' => $invoice,
        ]);
    }
}