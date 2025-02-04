<?php

namespace App\Controller;

use App\Entity\DTO\UserDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'register')]
    public function index(
        #[MapRequestPayload] UserDTO $dto,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $manager
    ): Response
    {
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $user = new User();
        $user->setName($dto->name);
        $user->setEmail($dto->email);
        $user->setPassword($passwordHasher->hashPassword($user, $dto->password));
        $manager->persist($user);
        $manager->flush();

        return $this->json('Registration successful', 200, ['Content-Type' => 'application/json']);
    }
}
