<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Comment;

class Ticket extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'first_assignation' => 'datetime',
        'last_assignation' => 'datetime',
    ];


    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'id_proprietaire');
    }

    public function assignation()
    {
        return $this->belongsTo(User::class, 'id_assignation');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_ticket'); 
    }
}
