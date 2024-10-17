<?php

// app/Http/Resources/TaskResource.phpnamespace App\Http\Resources;

use App\Http\Resources\AttachmentResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\TaskDependencyResource;
use App\Http\Resources\TaskStatusUpdateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
            'assigned_to' => $this->assigned_to,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),
            'status_updates' => TaskStatusUpdateResource::collection($this->whenLoaded('statusUpdates')),
            'dependencies' => TaskDependencyResource::collection($this->whenLoaded('dependencies')),
        ];
    }
}