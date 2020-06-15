<?php

namespace Modules\Ticket\Http\Resources\V1;
use Neon\Http\User\Resources\UserAlias;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Ticket\Http\Resources\V1\SubTicket as SubTicketResource;


class TicketCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'user' => new UserAlias($this->whenLoaded('user')),
            'firstSubTicket' => new SubTicketResource(SubTicket::where('ticket_id',$this->id)->first()),
            'importance' => $this->importance,
            'subject' => $this->subject,
            'status' => $this->status,
            'state' => $this->state,
            'date' => jstrftime('%Y %B %e',$this->created_at->timestamp),
            'time' => date_format($this->created_at,"H:i")
        ];
    }
}
