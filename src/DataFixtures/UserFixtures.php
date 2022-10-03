<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Command\UserPasswordHashCommand;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher  
    )
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $password = $this->passwordHasher->hashPassword($user, '123@456');

        $user
            ->setUsername('usuario_testes')
            ->setPassword($password)
        ;

        $manager->persist($user);

        $manager->flush();
    }
}
