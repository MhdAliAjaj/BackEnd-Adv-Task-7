<?php
namespace App\Services;

use App\Models\Task;
use App\Models\Comment;
use App\Models\Attachment;
use App\Models\TaskStatusUpdate;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function getAllTasks($filters)
    {
        $tasks = Task::query();

        if (isset($filters['type'])) {
            $tasks->where('type', $filters['type']);
        }

        if (isset($filters['status'])) {
            $tasks->where('status', $filters['status']);
        }

        if (isset($filters['assigned_to'])) {
            $tasks->where('assigned_to', $filters['assigned_to']);
        }

        if (isset($filters['due_date'])) {
            $tasks->where('due_date', $filters['due_date']);
        }

        if (isset($filters['priority'])) {
            $tasks->where('priority', $filters['priority']);
        }

        if (isset($filters['depends_on'])) {
            $tasks->whereDoesntHave('dependencies', function ($query) use ($filters) {
                $query->where('depends_on', $filters['depends_on']);
            });
        }

        return $tasks->get();
    }

    public function createTask($data)
    {
        return Task::create($data);
    }

    public function getTaskById($id)
    {
        return Task::with(['comments', 'attachments', 'statusUpdates', 'dependencies'])->findOrFail($id);
    }

    public function updateTaskStatus($id, $status)
    {
        $task = Task::findOrFail($id);
        $task->status = $status;
        $task->save();

        TaskStatusUpdate::create([
            'task_id' => $task->id,
            'status' => $status,
            'updated_by' => Auth::id(),
        ]);

        return $task;
    }

    public function reassignTask($id, $assigned_to)
    {
        $task = Task::findOrFail($id);
        $task->assigned_to = $assigned_to;
        $task->save();

        return $task;
    }

    public function addCommentToTask($id, $body)
    {
        $task = Task::findOrFail($id);

        $comment = new Comment([
            'body' => $body,
            'user_id' => Auth::id(),
        ]);

        $task->comments()->save($comment);

        return $comment;
    }

    public function addAttachmentToTask($id, $file)
    {
        $task = Task::findOrFail($id);

        $path = $file->store('attachments');

        $attachment = new Attachment([
            'file_path' => $path,
            'user_id' => Auth::id(),
        ]);

        $task->attachments()->save($attachment);

        return $attachment;
    }

    public function assignTask($id, $assigned_to)
    {
        $task = Task::findOrFail($id);
        $task->assigned_to = $assigned_to;
        $task->save();

        return $task;
    }

    public function getDailyReport()
    {
        return Task::whereDate('created_at', now()->toDateString())->get();
    }
}