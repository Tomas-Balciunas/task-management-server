<?php

namespace App\Controller;

use App\Entity\DTO\UserLoginDTO;
use App\Repository\UserRepository;
use App\Service\JWTHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(
        Request $request,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher,
        JWTHandler $JWTHandler,
        #[MapRequestPayload] UserLoginDTO $dto
    ): Response
    {
        $data = json_decode($request->getContent(), true);

        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return $this->json(['error' => 'Invalid credentials.'], Response::HTTP_BAD_REQUEST);
        }

        if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
            return $this->json(['error' => 'Invalid credentials.'], Response::HTTP_BAD_REQUEST);
        }

        $token = $JWTHandler->generateToken(['email' => $user->getEmail()]);

        return $this->json(['token' => $token], Response::HTTP_OK);
    }
}