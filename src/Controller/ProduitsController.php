<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Produits;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ProduitsController extends AbstractController{

    #[Route('/produits', name:'app_produits')]
    public function afficherProduit(ProduitsRepository $produitsRepository):Response{
        
        return $this->render('produits/afficher_produits.html.twig',[
            'produits' => $produitsRepository->findAll()
        ]);
    }

    #[Route('/details-produit/{id}', name:'app_details_produit')]
    public function detailsProduit(
        Produits $produits,
        Request $request,
        EntityManagerInterface $em,
        CommentairesRepository $commentairesRepository,
        ):Response{
        //instance de l'entité Commentaires
        $commentaires = new Commentaires();
        //Création du formulaire
        $form = $this->createForm(CommentairesType::class, $commentaires);
        //Récupération des <input name=""/>
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //Assigner le produit a la table Commentaires (cle etrangère = id produits)
            $commentaires->setProduits($produits);
            //Ajout du commentaire a la table Produits
            $produits->addCommentaire($commentaires);
            $em->persist($commentaires);
            $em->flush();
     
            $this->addFlash('success','Votre commentaire a bien été enregistré');
            return new JsonResponse(['success' => true]);
        }
        return $this->render('produits/details-produit.html.twig',[
            'produit' => $produits,
            'form' => $form->createView(),
            'commentaires' => $commentairesRepository->findAll()
        ]);
    }

    //Afficher le produit au format json
    //Creation de l'api
    #[Route('/produits-json', name:'app_json_produits')]
    public function produitJson(
        ProduitsRepository $produitsRepository, 
        SerializerInterface $serializer) : JsonResponse {
        //Recuperer tous les Produits
        $produits = $produitsRepository->findAll();
        //On serialise les données Produits au format json => seulement les propriétés qui appartiennent au groupe => produits
        $produits_to_json = $serializer->serialize($produits, 'json', ['groups' => 'produits']);
        //PHP serialisé + Response::HTTP_OK = 200, [options headers request], true = les données sont déja serialisées
        return new JsonResponse($produits_to_json, Response::HTTP_OK, [], true);
        
    }

    #[Route('/produits-json/{id}', name:'app_json_one_produits')]
    public function oneProduitJson(
        int $id,
        ProduitsRepository $produitsRepository, 
        SerializerInterface $serializer) : JsonResponse {
        //Recuperer tous les Produits
        $produits = $produitsRepository->find($id);
        //On serialise les données Produits au format json => seulement les propriétés qui appartiennent au groupe => produits
        $produits_to_json = $serializer->serialize($produits, 'json', ['groups' => 'produits']);
        //PHP serialisé + Response::HTTP_OK = 200, [options headers request], true = les données sont déja serialisées
        return new JsonResponse($produits_to_json, Response::HTTP_OK, [], true);
        
    }

    #[Route('/afficher-produits-json', name:'app_afficher_json_produits')]
    public function afficherProduitJson():Response{

        return $this->render('produits/produits_json.html.twig');
    }
}
