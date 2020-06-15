<?php


namespace Modules\Ticket\Http\Requests\V1;


use Neon\App\Requests\ApiRequest;

class TicketUpdateRequest extends ApiRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'text' => 'nullable|string',
            'importance' => 'nullable|string',
            'subject' => 'nullable|string'
        ];

    }

}
