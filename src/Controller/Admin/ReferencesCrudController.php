<?php

namespace App\Controller\Admin;

use App\Entity\References;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReferencesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return References::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //Cacher ID 
            IdField::new('id')->onlyOnIndex(),
            //Entrer la référence
            TextField::new('name', 'Référence du produit'),
            //Choisir le produit concerné
            AssociationField::new('produits')->autocomplete(),
        ];
    }
    
}
