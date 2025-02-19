<?php

namespace App\Entity\DTO;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class TaskDTO
{
    
    #[Assert\NotBlank(message: 'Task title cannot be blank.')]
    #[Groups(['create', 'update'])]
    public string $title;

    #[Assert\NotBlank(message: 'Task description cannot be blank.')]
    #[Groups(['create', 'update'])]
    public string $description;

    #[Assert\NotNull(message: 'Task priority must be specified.')]
    #[Assert\Choice(callback: [TaskPriority::class, 'values'], message: 'Task priority is incorrect.')]
    #[Groups(['create', 'update'])]
    public string $priority;

    #[Assert\NotNull(message: 'Task status must be specified.')]
    #[Assert\Choice(callback: [TaskStatus::class, 'values'], message: 'Task status is incorrect.')]
    #[Groups(['update'])]
    public string $status;

    #[Assert\NotBlank(message: "Task's due date must be specified.")]
    #[Assert\Type('DateTimeInterface', message: "Due date must have a valid date and time format.")]
    #[Assert\GreaterThan('now', message: "Task's due date must be set in the future.")]
    #[Groups(['create', 'update'])]
    public \DateTimeInterface|bool $dueDate;
}