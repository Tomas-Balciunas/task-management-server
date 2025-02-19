<?php

namespace App\Helper;

use App\Constants\TaskConstants;
use App\Entity\DTO\TaskDTO;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskPriority;

class TaskHelper
{
    public function buildTaskDTO(array $data): TaskDTO
    {
        $dto = new TaskDTO();
        $dto->title = $data['title'];
        $dto->description = $data['description'];
        $dto->priority = $data['priority'];
        $dto->dueDate = new \DateTime($data['dueDate'], new \DateTimeZone('UTC'));

        return $dto;
    }

    public function buildTask(TaskDTO $dto, User $me, User $user): Task
    {
        $task = new Task();
        $task->setTitle($dto->title);
        $task->setDescription($dto->description);
        $task->setPriority(TaskPriority::from($dto->priority));
        $task->setStatus(TaskConstants::DEFAULT_STATUS);
        $task->setDueDate($dto->dueDate);

        $task->setAssignedTo($user);
        $task->setAssignedBy($me);

        return $task;
    }
}