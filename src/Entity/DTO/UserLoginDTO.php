<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserLoginDTO
{
    #[Assert\NotBlank(message: 'Email is required.')]
    #[Assert\Email(message: 'Email is not valid.')]
    public string $email;

    #[Assert\NotBlank(message: 'Password is required.')]
    public string $password;
}