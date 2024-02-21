<?php

namespace App\Controller;

use App\Entity\Professional;
use App\Form\ProfessionalType;
use App\Repository\ProfessionalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/customers/professional')]
class ProfessionalController extends AbstractController
{
    #[Route('/', name: 'app_professional_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMINISTRATIVE')]
    public function index(ProfessionalRepository $professionalRepository): Response
    {
        return $this->render('professional/index.html.twig', [
            'professionals' => $professionalRepository->findAllQuery(),
        ]);
    }

    #[Route('/new', name: 'app_professional_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMINISTRATIVE')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $professional = new Professional();
        $form = $this->createForm(ProfessionalType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $professional->setDischarge(false);
            $entityManager->persist($professional);
            $entityManager->flush();

            return $this->redirectToRoute('app_professional_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('professional/new.html.twig', [
            'professional' => $professional,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_professional_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMINISTRATIVE')]
    public function show(Professional $professional): Response
    {
        return $this->render('professional/show.html.twig', [
            'professional' => $professional,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_professional_edit', methods: ['GET', 'POST'])]

    #[IsGranted('ROLE_ADMINISTRATIVE')]
    public function edit(Request $request, Professional $professional, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProfessionalType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_professional_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('professional/edit.html.twig', [
            'professional' => $professional,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_professional_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMINISTRATIVE')]
    public function delete(Request $request, Professional $professional, EntityManagerInterface $entityManager): Response
    {
        /*if ($this->isCsrfTokenValid('delete'.$professional->getId(), $request->request->get('_token'))) {
            $entityManager->remove($professional);
            $entityManager->flush();
        }*/

        $professional->setDischarge(true);
        $entityManager->persist($professional);
        $entityManager->flush();

        return $this->redirectToRoute('app_professional_index', [], Response::HTTP_SEE_OTHER);
    }
}
