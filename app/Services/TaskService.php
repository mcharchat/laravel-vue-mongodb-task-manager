<?php
namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Collection;

class TaskService
{
    public function getTaskParticipantsIds(Task $task) : Collection
    {
        return collect($task->team)->merge([$task->user_id, $task->assigned_to])->unique()->filter(function ($value) {
            return !is_null($value);
        });
    }
}