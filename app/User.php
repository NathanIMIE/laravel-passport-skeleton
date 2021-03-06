<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Passport\HasApiTokens;

use App\Ticket;
use App\Comment;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ownedTickets()
    {
        return $this->hasMany(Ticket::class, 'id_proprietaire'); 
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'id_assignation'); 
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'author'); 
    }
}
