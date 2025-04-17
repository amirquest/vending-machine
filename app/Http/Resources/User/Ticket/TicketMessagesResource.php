<?php

namespace App\Http\Resources\User\Ticket;

use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketMessagesResource extends JsonResource
{
   public function toArray(Request $request): array
    {
        /** @var TicketMessage $model */
        $model = $this->resource;

        return [
            'message' => $model->message,
            'responder' => [
                'type' => $model->responder_type,
                'full_name' => $model->responder->full_name,
            ],
            'created_at' => $model->created_at
        ];
    }
}
