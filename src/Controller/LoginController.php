<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController{

    #[Route('/', name:'app_login')]
    public function connexion(AuthenticationUtils $authenticationUtils):Response{
        
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('connexion/connexion.html.twig',[
            'last_username' => $lastUsername,
            'error'         => $error,
            'username_label' => 'ADMIN',
            'username_parameter' => 'my_custom_username_field',
        ]);
    }

    #[Route('/deconnexion', name:'app_logout')]
    public function deconnexion(){
        return throw new \LogicException("Vous etes deconnecter");
    }
}