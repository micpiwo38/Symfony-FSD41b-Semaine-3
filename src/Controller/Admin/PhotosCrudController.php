<?php

namespace App\Controller\Admin;

use App\Entity\Photos;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;

class PhotosCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Photos::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            //Propriété de la table Photos
            ImageField::new('name')
                //Par defaut en lecture seule -> chemin ou sont stocké les images
                ->setBasePath('/img')
                //Destination des images téléchargées
                ->setUploadDir('public/img/')
                //Type de champ du formulaire => <input type="file"/>
                ->setFormType(FileUploadType::class)
        ];
    }
    
}
