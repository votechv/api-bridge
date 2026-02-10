<?php

namespace App\Service;

use App\Entity\User;
use App\Dto\RegisterUserDto;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService{

    public function __construct(
        private EntityManagerInterface $manager,
        private UserPasswordHasherInterface $passwordHasher,
    ){

    }

    public function registerUser(RegisterUserDto $dto): User{

        $user = new User();
        $user->setName($dto->name);
        $user->setEmail($dto->email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->password));

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }

}
