<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController{

    #[Route('/api-platform/produits', name: 'api_platform_produits')]
    public function produitsApi():Response{

        return $this->render('produits/produits_api.html.twig');
    }
}