<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['hashPassword'],
            BeforeEntityUpdatedEvent::class => ['hashPassword'],
        ];
    }

    public function hashPassword(BeforeEntityPersistedEvent|BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        // Récupérer le mot de passe en clair
        $plainPassword = $entity->getPassword();

        // Si le mot de passe est vide (lors d'une modification), ne rien faire
        if (empty($plainPassword)) {
            return;
        }

        // Si le mot de passe commence par '$2y$', c'est qu'il est déjà hashé
        if (str_starts_with($plainPassword, '$2y$')) {
            return;
        }

        // Hasher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($entity, $plainPassword);
        $entity->setPassword($hashedPassword);
    }
}
