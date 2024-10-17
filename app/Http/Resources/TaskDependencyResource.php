<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskDependencyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'depends_on' => $this->depends_on,
            'created_at' => $this->created_at,
        ];
    }
}