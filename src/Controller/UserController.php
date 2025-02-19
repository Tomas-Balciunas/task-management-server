<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/user')]
class UserController extends AbstractController
{
    #[Route('/me', name: 'user', methods: ['GET'])]
    public function me(#[CurrentUser] User $me): Response
    {
        return $this->json([
            'name' => $me->getName(),
            'email' => $me->getEmail()
        ], Response::HTTP_OK);
    }

    #[Route('/profile', name: 'profile', methods: ['GET'])]
    public function get(#[CurrentUser] User $me): Response
    {
        return $this->json([
            'name' => $me->getName(),
            'email' => $me->getEmail(),
            'tasks' => $me->getTasks(),
        ], Response::HTTP_OK,
            [],
            ['groups' => ['user_profile']]);
    }

    #[Route('/all', name: 'all_users', methods: ['GET'])]
    public function all(UserRepository $repository): Response
    {
        return $this->json([$repository->findAllBase()], Response::HTTP_OK);
    }
}