<?php

namespace App\Enum;

enum TaskStatus: string
{
    use ValueTrait;

    case TO_DO = 'To Do';
    case IN_PROGRESS = 'In Progress';
    case COMPLETED = 'Completed';
}