<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tasks'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user_profile', 'tasks'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['user_profile', 'tasks'])]
    private ?string $description = null;

    #[ORM\Column(enumType: TaskStatus::class)]
    #[Groups(['user_profile', 'tasks'])]
    private ?TaskStatus $status = null;

    #[ORM\Column(enumType: TaskPriority::class)]
    #[Groups(['user_profile', 'tasks'])]
    private ?TaskPriority $priority = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['user_profile', 'tasks'])]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
    #[Groups(['tasks'])]
    private ?User $assignedTo;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'assignedTasks')]
    #[Groups(['user_profile', 'tasks'])]
    private ?User $assignedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?TaskStatus
    {
        return $this->status;
    }

    public function setStatus(TaskStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?TaskPriority
    {
        return $this->priority;
    }

    public function setPriority(TaskPriority $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getAssignedBy(): ?User
    {
        return $this->assignedBy;
    }

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setAssignedBy(?User $assignedBy): void
    {
        $this->assignedBy = $assignedBy;
    }

    public function setAssignedTo(?User $assignedTo): void
    {
        $this->assignedTo = $assignedTo;
    }
}
