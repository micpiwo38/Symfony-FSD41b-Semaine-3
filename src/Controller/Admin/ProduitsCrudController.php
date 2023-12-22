<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Produits;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ProduitsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produits::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name', 'Nom du produit'),
            TextField::new('slug', 'Nom du produit dans URL'),
            TextareaField::new('description', 'Description du produit')->renderAsHtml(),
            MoneyField::new('price', 'Prix du produit')
            ->setCurrency('EUR')
            ->setCustomOption('storedAsCents', false),
            //Associé l'entité References à l'aide du champ reference_id de l'entité Produits
            AssociationField::new('reference')->renderAsEmbeddedForm(),
            //Associé l'entité Categories à l'aide du champ categorie_id de l'entité Produits
            AssociationField::new('categorie')->autocomplete(),
            //Associé l'entité Distributeurs à l'aide de la table intermédaire produits_distributeurs
            AssociationField::new('distributeur')->autocomplete(),
            //Afficher la 1er image du tableau de photo
            ImageField::new('photos[0]', 'Images')
            //Seulement sur la page d'accueil du Tableau de bord
                ->onlyOnIndex()
                //Par defaut en lecture seule -> chemin ou sont stocké les images
                ->setBasePath('/build/images/')
                
                //Destination des images téléchargées
                ->setUploadDir('public/build/images/'),
                //Accès à la collection de photo
            CollectionField::new('photos')
            //Seuelement sur la page Ajouter et Editer
                ->onlyOnForms()
                //Appel du PhotosCrudController
                ->useEntryCrudForm(PhotosCrudController::class),
            BooleanField::new('avalable', 'Produits est disponible'),     
            DateTimeField::new('createdAt', 'Date de création'),      
        ];
    }
    
}
