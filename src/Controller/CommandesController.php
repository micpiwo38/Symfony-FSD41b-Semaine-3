<?php

namespace App\Controller;

use App\Entity\CommandeDetails;
use App\Entity\Commandes;
use App\Repository\CommandeDetailsRepository;
use App\Repository\CommandesRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

class CommandesController extends AbstractController{
    #[Route('/commandes', name:'app_valider_commandes')]
    public function ajouterCommande(
        SessionInterface $session,
        ProduitsRepository $produitsRepository,
        EntityManagerInterface $em
    ):Response{
        //Imposer une connexion utilisateur
        $this->denyAccessUnlessGranted('ROLE_USER');
        //Récuperer le panier à l'aide de SessionInterface
        $panier = $session->get('panier', []);
        //Le panier n'est pas vide on creer la commande
        //Instance de l'entité Commandes
        $commande = new Commandes();
        //On remplit la commande à l'aide des setters
        //Utilisateur concerné
        $commande->setUser($this->getUser());
        //Numero de la commande => id randrom
        $commande->setNumeroCmd(uniqid());
        //Calcul du total
        $total = 0;
        //Parcous du panier par reférence pour les details de la commande
        foreach($panier as $item => $quantite){
            //Instance de l'entité CommandeDetails
            $commande_details = new CommandeDetails();
            $produit = $produitsRepository->find($item);
            //dd($produit);
            $prix = $produit->getPrice();
            //Remplir les details de la commande
            $commande_details->setProduits($produit);
            $commande_details->setPrix($prix);
            $commande_details->setQuantite($quantite);
            //Ajout des details a la commande principale (parente)
            //Utilisation de la methode addCommandeDetails => genere par la relation OnToMany
            $commande->addCommandeDetail($commande_details);
        }

        $em->persist($commande);
        $em->flush();

        //On vide le panier
        $session->remove('panier');
        $this->addFlash('success', 'Votre commande à bien été validée !');
        return $this->redirectToRoute('app_resume_commande');
    }

    #[Route('/resume-commandes', name:'app_resume_commande')]
    public function resumeCommande(
        CommandesRepository $commandesRepository,
        CommandeDetailsRepository $commandeDetailsRepository 
    ):Response{
        $details = $commandeDetailsRepository->findAll();
        //dd($details);
        $total = 0;
        $element = 0;
        foreach($details as $item){
            $montant_total = $item->getPrix() * $item->getQuantite();
            $total += $montant_total;
            
        }
        return $this->render('commandes/resume-commande.html.twig',[
            'commandes' => $commandesRepository->findAll(),
            'details_commande' => $commandeDetailsRepository->findAll(),
            'total' => $total
            
        ]);
    }
}