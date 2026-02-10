<?php

namespace App\Dto;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[UniqueEntity(fields: ['email'], message: 'Email already registered', entityClass: User::class)]
class RegisterUserDto{
    #[Assert\NotBlank(message: 'Name is required')]
    public string $name;
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'Invalid email format')] public string $email;

    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\PasswordStrength(minScore: PasswordStrength::STRENGTH_MEDIUM)]
    public string $password;
}
