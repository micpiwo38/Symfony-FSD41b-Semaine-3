<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CategoriesController extends AbstractController{

    #[Route('/categories', name:'app_categories')]
    public function afficherCategorie(CategoriesRepository $categoriesRepository):Response{

        return $this->render('categories/afficher_categories.html.twig',[
            'categories' => $categoriesRepository->findAll()
        ]);
    }

    #[Route('/ajouter-categories', name:'app_ajouter_categories')]
    public function ajouterCategories(
        Request $request,
        EntityManagerInterface $em
        ):Response{
        
        $categorie = new Categories();
        $form = $this->createForm(CategoriesFormType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'La catégorie a bien été ajoutée !');
            return $this->redirectToRoute('app_categories');
        }

        return $this->render('categories/ajouter_categories.html.twig',[
            'form' => $form->createView()
        ]);

    }
}