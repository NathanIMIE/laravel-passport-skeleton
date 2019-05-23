<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

use App\Ticket;
use App\User;

class CommentTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        $id = $request->route('id');
        $ticket = Ticket::findOrFail($id);

        return $this->user()->id == $ticket->id_proprietaire || $this->user()->id == $ticket->id_assignation;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required|string|max:255',
        ];
    }
}
