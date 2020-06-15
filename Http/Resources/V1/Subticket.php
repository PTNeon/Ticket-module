<?php

namespace Modules\Ticket\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Neon\Domain\Users\User;
use Neon\Http\User\Resources\UserAlias;

class Subticket extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'user' => new UserAlias(User::find($this->user_id)),
            'text' => $this->text,
            'date' => jstrftime('%Y %B %e',$this->created_at->timestamp),
            'time' => date_format($this->created_at,"H:i"),
            'attachments' => json_decode($this->attachments,true)
        ];
    }
}
