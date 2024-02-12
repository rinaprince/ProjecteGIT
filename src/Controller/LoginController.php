<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        if ($authorizationChecker->isGranted('ROLE_LOGIN')) {
            return $this->redirectToRoute('app_front_office');
        }

        return $this->render('login/login.html.twig', [
            'last_username'=> $lastUsername,
            'error'        => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {

    }

    #[Route('/profile', name: 'app_profile')]
    public function edit(CustomerRepository $customerRepository, EmployeeRepository $employeeRepository): Response
    {
        return $this->render('profile/profile.html.twig');
    }


}
