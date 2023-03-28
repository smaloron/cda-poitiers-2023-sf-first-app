<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    #[Route('/login', name: 'app_security_login')]
    public function login(AuthenticationUtils $auth){
        return $this->render('login.html.twig', [
            'error' => $auth->getLastAuthenticationError(),
            'userName' => $auth->getLastUsername()
        ]);
    }

}