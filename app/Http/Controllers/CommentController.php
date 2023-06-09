<?php

namespace App\Http\Controllers;

use App\Events\CommentPublicTaskEvent;
use App\Events\CommentPrivateTaskEvent;
use App\Models\Comment;
use App\Models\Task;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request) : RedirectResponse
    {
        //validate the incoming request using our StoreCommentRequest
        $validated = $request->validated();
        // set the user_id to the current user id
        $validated['user_id'] = auth()->id();
        // add the squad_id to the validated data
        $validated['squad_id'] = auth()->user()->squad_id;
        // create a new task with the validated data
        Comment::create($validated);
        // get the task that the comment belongs to, with all of its comments
        $task = Task::find($validated['task_id'])->load('comments');
        $message = [
            'task_id' => $validated['task_id'], 
            'task_title' => $task->title,
            'user_id' => auth()->id(),
            'comments' => $task->comments,
        ];

        //if task is public, fire the CommentPublicTaskEvent event with the squad_id and the validated data, else merge user_id, assigned_to and squad_id to one array, remove duplicates and null values and fire the CommentPrivateTaskEvent event with validated data 
        if ($task->public) {
            event(new CommentPublicTaskEvent(auth()->user()->squad_id, $message, 'create'));
        } else {
            $participants_ids = collect(array_merge([$task->user_id], [$task->assigned_to], $task->team))
                ->unique()
                ->filter(function ($value) {
                    return !is_null($value);
                });
            event(new CommentPrivateTaskEvent($participants_ids, $message, 'create'));
        }
        // return a redirect to the tasks index
        return redirect()->back(303)->with('success', 'Comment made successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
