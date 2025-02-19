<?php

namespace App\Controller;

use App\Entity\DTO\TaskDTO;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/tasks', name: 'tasks')]
final class TaskController extends AbstractController
{
    public function __construct(private readonly TaskService $service)
    {
    }

    #[Route('', methods: ['GET'])]
    public function index(TaskRepository $repository): Response
    {
        $tasks = $repository->findAll();

        return $this->json($tasks, Response::HTTP_OK, ['Content-Type' => 'application/json'], ['groups' => ['tasks']]);
    }

    #[Route('/{id}', name: 'task', methods: ['GET'])]
    public function get(Task $task): Response
    {
        return $this->json($task, Response::HTTP_OK, ['Content-Type' => 'application/json'], ['groups' => ['tasks']]);
    }

    #[Route('/new', name: 'new_task', methods: ['POST'])]
    public function new(
        Request $request,
        #[CurrentUser] User $me
    ): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $this->service->handleNewTask($data, $me);
        } catch (\Throwable $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }


        return $this->json("Task has been created.", Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'update_task', methods: ['PUT'])]
    public function update(
        Task $task,
        #[MapRequestPayload] TaskDTO $dto,
    ): Response
    {
        try {
            $this->service->handleUpdateTask($dto, $task);
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json('Task has been updated.', Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'delete_task', methods: ['DELETE'])]
    public function delete(Task $task, EntityManagerInterface $manager): Response
    {
        $manager->remove($task);
        $manager->flush();

        return $this->json('Task has been deleted.', Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
