<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskStatusUpdateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
        ];
    }
}