<?php

namespace App\Listener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
class UserListener
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher){}

    public function prePersist(
        User $user, LifecycleEventArgs $event
    ): void
    {
        $user->setHashedPassword(
            $this->hasher->hashPassword($user, $user->getPlainPassword())
        );
    }

}