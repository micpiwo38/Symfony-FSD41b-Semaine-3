<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController{

    #[Route('/accueil', name:'app_accueil')]
    public function accueil():Response{
        
        return $this->render('accueil/accueil.html.twig');

    }
}