<?php

namespace App\Http\Resources\Admin\Ticket;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Ticket $model */
        $model = $this->resource;

        return [
            'identifier' => $model->identifier,
            'title' => $model->title,
            'status' => $model->status,
            'priority' => $model->priority,
            'user' => [
                'full_name' => $model->user->full_name,
            ],
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ];
    }
}
