<?php

namespace Modules\Ticket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Neon\Domain\Users\User;

class SubTicket extends Model
{
    use SoftDeletes;

    public $fillable=[
        'user_id','tickets_id','text'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
