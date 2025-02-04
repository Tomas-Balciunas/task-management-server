<?php

namespace App\Entity\DTO;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use Symfony\Component\Validator\Constraints as Assert;

class TaskDTO
{
    
    #[Assert\NotBlank(message: 'Task title cannot be blank.')]
    public string $title;

    #[Assert\NotBlank(message: 'Task description cannot be blank.')]
    public string $description;

    #[Assert\NotNull(message: 'Task priority must be specified.')]
    #[Assert\Choice(callback: [TaskPriority::class, 'values'], message: 'Task priority is incorrect.')]
    public string $priority;

    #[Assert\NotNull(message: 'Task status must be specified.')]
    #[Assert\Choice(callback: [TaskStatus::class, 'values'], message: 'Task status is incorrect.')]
    public string $status;

    #[Assert\NotBlank(message: "Task's due date must be specified.")]
    #[Assert\DateTime(message: "Due date must have a valid date and time format.")]
    public string $dueDate;
}