<?php

namespace App\Controller;

use App\Entity\DTO\TaskDTO;
use App\Entity\Task;
use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/task', name: 'task')]
final class TaskController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(TaskRepository $repository): Response
    {
        $tasks = $repository->findAll();

        return $this->json($tasks, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'task', methods: ['GET'])]
    public function get(Task $task): Response
    {
        return $this->json($task, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/new', name: 'new_task', methods: ['POST'])]
    public function new(
        #[MapRequestPayload] TaskDTO $dto,
        ValidatorInterface $validator,
        EntityManagerInterface $manager
    ): Response
    {
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;

            return $this->json($errorsString, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $task = new Task();
        $task->setTitle($dto->title);
        $task->setDescription($dto->description);
        $task->setPriority(TaskPriority::from($dto->priority));
        $task->setStatus(TaskStatus::from($dto->status));
        $task->setDueDate(new \DateTime($dto->dueDate));

        $manager->persist($task);
        $manager->flush();

        return $this->json("Task has been created.", Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'update_task', methods: ['PUT'])]
    public function update(
        Task $task,
        #[MapRequestPayload] TaskDTO $dto,
        ValidatorInterface $validator,
        EntityManagerInterface $manager
    ): Response
    {
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;

            return $this->json($errorsString, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $task->setTitle($dto->title);
        $task->setDescription($dto->description);
        $task->setPriority(TaskPriority::from($dto->priority));
        $task->setStatus(TaskStatus::from($dto->status));

        $manager->flush();


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
