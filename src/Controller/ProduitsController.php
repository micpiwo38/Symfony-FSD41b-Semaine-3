<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitsController extends AbstractController{

    #[Route('/produits', name:'app_produits')]
    public function afficherProduit(ProduitsRepository $produitsRepository):Response{
        
        return $this->render('produits/afficher_produits.html.twig',[
            'produits' => $produitsRepository->findAll()
        ]);
    }

    #[Route('/details-produit/{slug}', name:'app_details_produit')]
    public function detailsProduit(Produits $produits):Response{

        return $this->render('produits/details-produit.html.twig',[
            'produit' => $produits
        ]);
    }
}