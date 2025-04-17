<?php

namespace App\Http\Requests\User;

use App\Enums\Ticket\TicketPriorityEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
{
   public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:100'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
            'priority' => ['required', 'string', 'in:' . implode(',', TicketPriorityEnum::toArray())],
            'ticket_category_id' => ['required', 'exists:ticket_categories,id'],
        ];
    }
}
