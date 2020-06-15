<?php


namespace Modules\Ticket\Http\Requests\V1;


use Neon\App\Requests\ApiRequest;

class SubTicketStoreRequest extends ApiRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ticket_id' => 'numeric',
            'text' => 'nullable|string'
        ];

    }

}
