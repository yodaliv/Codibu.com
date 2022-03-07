<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Ticket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'user_id', 'category', 'ticket_id', 'title', 'priority', 'message', 'status',
    ];

    /**
     * A ticket belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A ticket can have many comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
