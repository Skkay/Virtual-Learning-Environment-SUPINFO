<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private $em;
    private $passwordHaser;

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHaser)
    {
        $this->em = $doctrine->getManager();
        $this->passwordHaser = $passwordHaser;
    }

    public function setPassword(User $user, string $password): void
    {
        $encodedPassword = $this->passwordHaser->hashPassword($user, $password);
        $user->setPassword($encodedPassword);

        $this->em->persist($user);
        $this->em->flush();
    }
}