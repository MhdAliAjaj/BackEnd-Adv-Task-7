<?php 
// app/Http/Controllers/TaskController.php
namespace App\Http\Controllers;

use App\Http\Resources\AttachmentResource;
use App\Http\Resources\CommentResource;
use App\Services\TaskService;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->middleware('auth:api');
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        $tasks = $this->taskService->getAllTasks($request->all());
        return TaskResource::collection($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:Bug,Feature,Improvement',
            'status' => 'required|in:Open,In Progress,Completed,Blocked',
            'priority' => 'required|in:Low,Medium,High',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task = $this->taskService->createTask($request->all());
        return new TaskResource($task);
    }

    public function show($id)
    {
        $task = $this->taskService->getTaskById($id);
        return new TaskResource($task);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Open,In Progress,Completed,Blocked',
        ]);

        $task = $this->taskService->updateTaskStatus($id, $request->status);
        return new TaskResource($task);
    }

    public function reassign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $task = $this->taskService->reassignTask($id, $request->assigned_to);
        return new TaskResource($task);
    }

    public function addComment(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $comment = $this->taskService->addCommentToTask($id, $request->body);
        return new CommentResource($comment);
    }

    public function addAttachment(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $attachment = $this->taskService->addAttachmentToTask($id, $request->file('file'));
        return new AttachmentResource($attachment);
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $task = $this->taskService->assignTask($id, $request->assigned_to);
        return new TaskResource($task);
    }

    public function dailyReport()
    {
        $tasks = $this->taskService->getDailyReport();
        return TaskResource::collection($tasks);
    }
}