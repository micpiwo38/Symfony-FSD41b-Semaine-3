<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriberUserPasswordChange implements EventSubscriberInterface{

    //Injection de EntityManagerInterface et UserPasswordHasherInterface au conteneur de services
    public function __construct(private EntityManagerInterface $em, private UserPasswordHasherInterface $passwordHasher){}

    public static function getSubscribedEvents(): array
    {
        //Les 2 evenements appel 2 methodes
        return [
            BeforeEntityPersistedEvent::class => ['ajouterUtilisateur'],
            BeforeEntityUpdatedEvent::class => ['miseJourUtilisateur']
        ];
    }

    public function ajouterUtilisateur(BeforeEntityPersistedEvent $event):void{
        //Recupereration de l'utilisateur
        $user = $event->getEntityInstance();
        //Si l'utilisateur n'est pas une instance de entité User
        if(!($user instanceof User)){
            return;
        }
        //Appel de la methode setPassword + user en paramètre
        $this->setPassword($user);
    }

    public function miseJourUtilisateur(BeforeEntityUpdatedEvent $event):void{
        //Recupereration de l'utilisateur
        $user = $event->getEntityInstance();
        if(!($user instanceof User)){
            return;
        }
        //Appel de la methode setPassword + user en paramètre
        $this->setPassword($user);
    }

    public function setPassword(User $user): void{
        //Recuperer le mot de passe de l'utilisateur connecté
        $password = $user->getPassword();
        //Hacher le mot de passe a l'aide de UserPasswordHasherInterface
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $password)
        );
        //Persistance de donnée et execution de la requète a l'aide de EntityManagerInterface
        $this->em->persist($user);
        $this->em->flush();
    }
}