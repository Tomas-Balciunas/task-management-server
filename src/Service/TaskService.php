<?php

namespace App\Service;

use App\Entity\DTO\TaskDTO;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Helper\TaskHelper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskService
{
    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly EntityManagerInterface $manager,
        private readonly ValidatorInterface     $validator,
        private readonly TaskHelper             $taskHelper,
    )
    {
    }

    public function handleNewTask(array $data, User $me): void
    {
        $user = $this->userRepository->findOneBy(['id' => $data['assignedTo']]);

        if (!$user) {
            throw new \Exception("User not found.");
        }

        $dto = $this->taskHelper->buildTaskDTO($data);

        $errors = $this->validator->validate($dto, [], ['create']);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;

            throw new \Exception($errorsString);
        }

        $task = $this->taskHelper->buildTask($dto, $me, $user);

        $this->manager->persist($task);
        $this->manager->flush();
    }

    public function handleUpdateTask(TaskDTO $dto, Task $task): void
    {
        $errors = $this->validator->validate($dto, [], ['update']);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;

            throw new \Exception($errorsString);
        }

        $task->setTitle($dto->title);
        $task->setDescription($dto->description);
        $task->setPriority(TaskPriority::from($dto->priority));
        $task->setStatus(TaskStatus::from($dto->status));
        $task->setDueDate($dto->dueDate);

        $this->manager->flush();
    }
}