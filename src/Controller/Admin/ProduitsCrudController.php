<?php

namespace App\Controller\Admin;

use App\Entity\Produits;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            TextEditorField::new('description', 'Description du produit'),
            MoneyField::new('price', 'Prix du produit')->setCurrency('EUR'),
            //Associé l'entité References à l'aide du champ reference_id de l'entité Produits
            AssociationField::new('reference')->autocomplete(),
            //Associé l'entité Categories à l'aide du champ categorie_id de l'entité Produits
            AssociationField::new('categorie')->autocomplete(),
            //Associé l'entité Distributeurs à l'aide de la table intermédaire produits_distributeurs
            AssociationField::new('distributeur')->autocomplete(),
            //Afficher la 1er image du tableau de photo
            ImageField::new('photos[0]', 'Images')
            //Seulement sur la page d'accueil du Tableau de bord
                ->onlyOnIndex()
                //Par defaut en lecture seule -> chemin ou sont stocké les images
                ->setBasePath('/img/')
                //Destination des images téléchargées
                ->setUploadDir('public/img/'),
                //Accès à la collection de photo
            CollectionField::new('photos')
            //Seuelement sur la page Ajouter et Editer
                ->onlyOnForms()
                //Appel du PhotosCrudController
                ->useEntryCrudForm(PhotosCrudController::class),
            BooleanField::new('avalable', 'Produits est disponible')           
        ];
    }
    
}
