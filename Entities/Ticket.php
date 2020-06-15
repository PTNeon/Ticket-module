<?php

namespace Modules\Ticket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Neon\Domain\Users\User;

class Ticket extends Model
{
    use SoftDeletes;
    public $fillable=[
        'user_id','importance','subject','status','state'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subtickets()
    {
        return $this->hasMany(SubTicket::class,'ticket_id','id');
    }
}
