<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController{

    #[Route('/ajouter-au-panier/{id}', name:'app_panier')]
    public function ajouterAuPanier(Produits $produits, SessionInterface $session):Response{
        //Récuperer l'id du produit concerner
        $produit_id = $produits->getId();
        //Recuperer le panier existant a l'aide du getter de la session
        //Par defaut dans la session le panier est vide (tableau associatif cle => valeur)
        $panier = $session->get('panier', []);
        //Si le panier est vide on ajoute l'id du produit => sinon on incremente
        if(empty($panier[$produit_id])){
            $panier[$produit_id] = 1;
        }else{
            $panier[$produit_id]++;
        }
        //On ajoute le panier a la session a l'aide du setter => (tableau associatif cle => valeur)
        $session->set('panier', $panier);
        //Une fois le produit ajouter on redirige vers la page panier
        return $this->redirectToRoute('app_afficher_panier');
    }

    #[Route('/afficher-panier', name:'app_afficher_panier')]
    public function afficherPanier(
        SessionInterface $session, 
        ProduitsRepository $produitsRepository):Response{
        //Recuperer le panier a partir de la session
        $panier = $session->get('panier', []);
        //dd($panier);
        //Stocker les produits de la session dans un nouveau tableau
        $commande = [];
        //Calculer le total de la commande
        $total = 0;

        //Itérer la valeur du tableau de paroduits constant par référence (quantité)
        foreach($panier as $id => $quantite){
            //Recuperer un produit
            $produit = $produitsRepository->find($id);
            //Stocker la commande dans un tableau assocaytif => cle : valeur
            $commande[] = [
                'produit' => $produit,
                'quantite' => $quantite
            ];
            //Calcul du total de la commande
            $total += $produit->getPrice() * $quantite; 
        }
        //Appel de la vue
        return $this->render('panier/afficher_panier.html.twig',[
            'panier' => $commande,
            'total' => $total
        ]);
    }

    //Ajouter une quantitée
    #[Route('/afficher-quantite-panier/{id}', name:'app_ajouter_quantite_panier')]
    public function ajouterQuantitePanier(
        Produits $produits, 
        SessionInterface $session) : Response {
            //Id du produit concerné
             //Récuperer l'id du produit concerner
            $produit_id = $produits->getId();
            //Recuperer le panier existant a l'aide du getter de la session
            //Par defaut dans la session le panier est vide (tableau associatif cle => valeur)
            $panier = $session->get('panier', []);
            //Si le panier est vide on ajoute l'id du produit => sinon on incremente
            if(empty($panier[$produit_id])){
                $panier[$produit_id] = 1;
            }else{
                $panier[$produit_id]++;
            }
            //On ajoute le panier a la session a l'aide du setter => (tableau associatif cle => valeur)
            $session->set('panier', $panier);
            //Une fois le produit ajouter on redirige vers la page panier
            return $this->redirectToRoute('app_afficher_panier');   
    }

     //Supprimer une quantitée
     #[Route('/supprimer-quantite-panier/{id}', name:'app_supprimer_quantite_panier')]
     public function supprimerQuantitePanier(
         Produits $produits, 
         SessionInterface $session) : Response {
             //Id du produit concerné
              //Récuperer l'id du produit concerner
             $produit_id = $produits->getId();
             //Recuperer le panier existant a l'aide du getter de la session
             //Par defaut dans la session le panier est vide (tableau associatif cle => valeur)
             $panier = $session->get('panier', []);
             //On retire le produit du panier si il y a 1 seul exemplaire => Sinon on decremente
             if(!empty($panier[$produit_id])){
                //Si la quantite est > 1
                if($panier[$produit_id] > 1){
                    $panier[$produit_id]--;
                }else{
                    //Defaire une variable php
                    //unset() détruit la ou les variables dont le nom a été passé en argument var. =>
                    // unset(mixed $var, mixed ...$vars): void
                    unset($panier[$produit_id]);
                }
             }
             //On ajoute le panier a la session a l'aide du setter => (tableau associatif cle => valeur)
             $session->set('panier', $panier);
             //Une fois le produit ajouter on redirige vers la page panier
             return $this->redirectToRoute('app_afficher_panier');   
     }

     //Supprimer un produit du panier
     #[Route('/supprimer-produit-panier/{id}', name:'app_supprimer_produit_panier')]
     public function supprimerProduitPanier(
        Produits $produits,
        SessionInterface $session
     ):Response{
        //Id du produit concerné
             //Récuperer l'id du produit concerner
             $produit_id = $produits->getId();
             //Recuperer le panier existant a l'aide du getter de la session
             //Par defaut dans la session le panier est vide (tableau associatif cle => valeur)
             $panier = $session->get('panier', []);
             //Si le panier n'est pas vide, on detruit la variable de session via unset()
             if(!empty($panier[$produit_id])){
                unset($panier[$produit_id]);
             }
             //On ajoute le panier a la session a l'aide du setter => (tableau associatif cle => valeur)
             $session->set('panier', $panier);
             $this->addFlash('success', 'Le produit à bien été supprimer du panier !');
             //Une fois le produit ajouter on redirige vers la page panier
             return $this->redirectToRoute('app_afficher_panier');  
     }

     #[Route('/vider-panier/', name:'app_vider_panier')]
     public function viderPanier(SessionInterface $session):Response{
        $session->remove('panier');
        $this->addFlash('success', 'Votre panier à été vidé !');
        return $this->redirectToRoute('app_afficher_panier');  
     }
}