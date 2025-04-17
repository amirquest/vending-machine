<?php

namespace App\Http\Resources\Admin\Ticket;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Ticket $model */
        $model = $this->resource;

        return [
          'identifier' => $model->identifier,
          'title' => $model->title,
          'category_name' => $model->category->name,
          'status' => $model->status,
          'priority' => $model->priority,
          'created_at' => $model->created_at,
          'messages' => TicketMessagesResource::make($model->messages),
        ];
    }
}
