<?php

namespace App\Controller;

use App\Repository\CommandesRepository;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController{

    #[Route('/accueil', name:'app_accueil')]
    public function accueil(
        ProduitsRepository $produitsRepository,
        CommandesRepository $commandesRepository
        ):Response{
        
        return $this->render('accueil/accueil.html.twig',[
            'produits' => $produitsRepository->findAll(),
            'commandes' => $commandesRepository->findAll()
        ]);
    }

    
}