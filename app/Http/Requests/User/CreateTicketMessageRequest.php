<?php

namespace App\Http\Requests\User;

use App\Enums\Ticket\TicketPriorityEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateTicketMessageRequest extends FormRequest
{
   public function rules(): array
    {
        return [
            'ticket_identifier' => ['required', 'numeric', 'exists:tickets,identifier'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }
}
