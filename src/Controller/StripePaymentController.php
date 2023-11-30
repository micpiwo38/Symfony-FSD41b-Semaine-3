<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Commandes;
use App\Entity\Produits;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripePaymentController extends AbstractController{
    #[Route('/commande/creer-session-stripe/{numero_cmd}', name:'app_paiement-stripe')]
    public function stripeCheckout(
        string $numero_cmd,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator
    ):RedirectResponse{
        //Tableau vide de la commande
        $produits_stripe = [];
        //Recuperer la commande à l'aide de son numero unique
        $commandes = $em->getRepository(Commandes::class)->findOneBy(['numero_cmd' => $numero_cmd]);
        //dd($commandes);
        //Si la commande est vide
        if(!$commandes){
            $this->addFlash('danger', 'Cette commande n\'existe pas !');
            $this->redirectToRoute('app_produits');
        }

        foreach($commandes->getCommandeDetails() as $produits){
            //dd($produits);
            //Recuperer un produit = acces au tableau + cle etrangere + nom entité produit par exemple
            //dd($produits->getProduits()->getName());
            //$un_produit = $em->getRepository(Produits::class)->findOneBy(['name' => $produits->getProduits()]);
            //dd($un_produit);
            //Ajout des produits au tableau de produits_stripe
            $produits_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $produits->getPrix(),
                    'product_data' => [
                        'name' => $produits->getProduits()->getName()
                    ]
                ],
                'quantity' => $produits->getQuantite()
            ];
        }

        //dd($produits_stripe);

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        header('Content-Type: application/json');

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getUserIdentifier(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                //Ici le tableau recap de la commande creer ci-dessus
                $produits_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $urlGenerator->generate('app_paiement_succes_stripe',[
                'numero_cmd' => $commandes->getNumeroCmd()
                ],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $urlGenerator->generate('app_paiement_erreur_stripe',[
                'numero_cmd' => $commandes->getNumeroCmd()
                ], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

        return new RedirectResponse($checkout_session->url);
    }

    //Si le paiement est accepter

    #[Route('/commande/succes-stripe/{numero_cmd}', name:'app_paiement_succes_stripe')]
    public function stripeSuccessPaiement(
    ):Response{
        return $this->render('stripe/succes_paiement.html.twig');
    }

    //Si le paiement est refusé
    #[Route('/commande/erreur-stripe/{numero_cmd}', name:'app_paiement_erreur_stripe')]
    public function stripeErrorPaiement(
    ):Response{
        return $this->render('stripe/erreur_paiement.html.twig');
    }
}