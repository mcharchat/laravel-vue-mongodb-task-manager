<?php
namespace App\Services;

use App\Events\CommentPublicTaskEvent;
use App\Events\CommentPrivateTaskEvent;

class CommentService 
{
    public function store(array $validated) : void
    {
        // create a new task with the validated data
        $comment = Comment::create($validated);
        // get the task that the comment belongs to, with all of its comments
        $task = $comment->task()->with('comments')->first();
        $message = [
            'task_id' => $task->id,
            'task_title' => $task->title,
            'user_id' => $task->user_id,
            'comments' => $task->comments,
        ];
        //if task is public, fire the CommentPublicTaskEvent event with the squad_id and the validated data, else merge user_id, assigned_to and squad_id to one array, remove duplicates and null values and fire the CommentPrivateTaskEvent event with validated data 
        if ($task->public) {
            broadcast(new CommentPublicTaskEvent($task->squad_id, $message, 'create'));
        } else {
            /** @var TaskService $taskService */
            $taskService = resolve(TaskService::class);
            broadcast(new CommentPrivateTaskEvent($taskService->getTaskParticipantsIds($task), $message, 'create'));
        }
    }
}