<?php

namespace App\Controller;

use App\Entity\Login;
use App\Entity\PrivateCustomer;
use App\Form\PrivateCustomerType;
use App\Repository\PrivateCustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/customers/private')]
class PrivateCustomerController extends AbstractController
{
    #[Route('/', name: 'app_private_customer_index', methods: ['GET'])]
    public function index(PrivateCustomerRepository $privateCustomerRepository): Response
    {
        return $this->render('private_customer/index.html.twig', [
            'private_customers' => $privateCustomerRepository->findAllQuery(),
        ]);
    }


/**
    #[Route('/new', name: 'app_private_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $privateCustomer = new PrivateCustomer();
        $form = $this->createForm(PrivateCustomerType::class, $privateCustomer);

        if ($request->getMethod() === 'POST')
            $form->submit($request->request->all());

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($privateCustomer);
            $entityManager->flush();

            return $this->redirectToRoute('app_private_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('private_customer/new.html.twig', [
            'private_customer' => $privateCustomer,
            'form' => $form,
        ]);
    } */


    #[Route('/new', name: 'app_private_customer_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMINISTRATIVE')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $privateCustomer = new PrivateCustomer();
        $form = $this->createForm(PrivateCustomerType::class, $privateCustomer);

        if ($request->getMethod() === 'POST' && $request->getContent()) {
            $data = $request->toArray();

            $form->submit($data);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $login = new Login();
            $login->setUsername($data["username"]);
            $login->setPassword($data["password"]);
            $login->setRole("ROLE_PRIVATE");
            $entityManager->persist($login);
            $privateCustomer->setLogin($login);
            $privateCustomer->setDischarge(false);
            $entityManager->persist($privateCustomer);
            $entityManager->flush();

            return $this->redirectToRoute('app_private_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('private_customer/new.html.twig', [
            'private_customer' => $privateCustomer,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_private_customer_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMINISTRATIVE')]
    public function show(PrivateCustomer $privateCustomer): Response
    {
        return $this->render('private_customer/show.html.twig', [
            'private_customer' => $privateCustomer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_private_customer_edit', methods: ['GET', 'POST'])]

    #[IsGranted('ROLE_ADMINISTRATIVE')]
    public function edit(Request $request, PrivateCustomer $privateCustomer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrivateCustomerType::class, $privateCustomer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_private_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('private_customer/edit.html.twig', [
            'private_customer' => $privateCustomer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_private_customer_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMINISTRATIVE')]
    public function delete(Request $request, PrivateCustomer $privateCustomer, EntityManagerInterface $entityManager): Response
    {
        /*if ($this->isCsrfTokenValid('delete'.$privateCustomer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($privateCustomer);
            $entityManager->flush();
        }*/

        $privateCustomer->setDischarge(true);
        $entityManager->persist($privateCustomer);
        $entityManager->flush();

        return $this->redirectToRoute('app_private_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
