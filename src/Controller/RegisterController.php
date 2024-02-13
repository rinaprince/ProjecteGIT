<?php

namespace App\Controller;

use App\Entity\Login;
use App\Entity\PrivateCustomer;
use App\Entity\Professional;
use App\Form\PrivateCustomerType;
use App\Form\ProfessionalType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RegisterController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }

    #[Route('/register', name: 'app_register')]
    public function index(): Response
    {
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }

    #[Route('/register/particular', name: 'app_register_private', methods: ['GET', 'POST'])]
    public function private(Request $request, EntityManagerInterface $entityManager): Response
    {
        $privateCustomer = new PrivateCustomer();
        $form = $this->createForm(PrivateCustomerType::class, $privateCustomer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $privateCustomer->getLogin()->setRole("ROLE_PRIVATE");
            //$entityManager->persist($login);
            //$privateCustomer->setLogin($login);
            $login = $privateCustomer->getLogin();
            $passwd = $login->getPassword();
            $login->setPassword($this->hasher->hashPassword($login,$passwd));
            $entityManager->persist($privateCustomer);
            $entityManager->flush();

            $this->addFlash('info', 'Usuari registrat correctament!');

            return $this->redirectToRoute('app_front_office', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('register/private.html.twig', [
            'private_customer' => $privateCustomer,
            'form' => $form,
        ]);
    }

    #[Route('/register/company', name: 'app_professional_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMINISTRATIVE')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $professional = new Professional();
        $form = $this->createForm(ProfessionalType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($professional);
            $entityManager->flush();

            return $this->redirectToRoute('app_professional_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('professional/register.html.twig', [
            'professional' => $professional,
            'form' => $form,
        ]);
    }
}
