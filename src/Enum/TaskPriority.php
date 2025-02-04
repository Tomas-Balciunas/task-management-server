<?php

namespace App\Enum;

enum TaskPriority: string
{
    use ValueTrait;

    case Low = 'Low';
    case Medium = 'Medium';
    case High = 'High';
}