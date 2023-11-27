<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\ProduitsCrudController;
use App\Entity\Categories;
use App\Entity\Distributeurs;
use App\Entity\Photos;
use App\Entity\Produits;
use App\Entity\References;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Option 1. 
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        //Par defaut on affiche le contenu du ProduitsCrudControlleur
        return $this->redirect($adminUrlGenerator->setController(ProduitsCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        //Nom du tableau de bord
            ->setTitle('SYMFONY 6 ADMINSTRATION')
            //Inclure une image HTML
            ->setTitle('<img src="img/logo_sf6.png"" alt="symfony_6" title="symfony 6"/>')
            //Icone
            //->setFaviconPath('favicon.svg')
            //->setTranslationDomain('fichier-de-traduction')
            //Contenu sur toutes la page
            ->renderContentMaximized()
            //Menu de droite minifié
            //->renderSidebarMinimized()
            //Desactivé le dark mode
            //->disableDarkMode()
            //URL relative => absolue par defaut
            ->generateRelativeUrls()
            //Langue par defaut
            ->setLocales(['fr']);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        if(!$user instanceof User){
            throw new Exception("Erreur");
        }
        return parent::configureUserMenu($user)
        ->setGravatarEmail($user->getEmail())
        ->setName($user->getEmail())
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Produits', 'fa-brands fa-product-hunt', Produits::class);
        yield MenuItem::linkToCrud('Catégorie', 'fas fa-paperclip', Categories::class);
        yield MenuItem::linkToCrud('Distributeurs', 'fas fa-file', Distributeurs::class);
        yield MenuItem::linkToCrud('Photos', 'fas fa-camera', Photos::class);
        yield MenuItem::linkToCrud('Références Produits', 'fas fa-calculator', References::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
    }
}
