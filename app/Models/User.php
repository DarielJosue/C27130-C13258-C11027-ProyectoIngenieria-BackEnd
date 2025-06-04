<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id'; 

    public $timestamps = false;

    protected $fillable = [
        'name',
        'lastname',
        'username',
        'email',
        'password',
        'registration_date',
    ];
    protected $hidden = [
        'password',
    ];

    /**
     * para los mensajes y conversaciones
     */
    public function conversationsAsUserOne()
    {
        return $this->hasMany(Conversation::class, 'user_one_id', 'user_id');
    }

    public function conversationsAsUserTwo()
    {
        return $this->hasMany(Conversation::class, 'user_two_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id', 'user_id');
    }

    public function getAuthPassword()
    {
        return $this->attributes['password'];
    }

    public function emails()
    {
        return $this->hasMany(Email::class, 'user_id', 'user_id');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }
    public function applications()
    {
        return $this->hasMany(Application::class, 'user_id', 'user_id');
    }
    public function curriculum()
    {
        return $this->hasMany(Curriculum::class, 'user_id', 'user_id');
    }
    public function jobPosts()
    {
        return $this->hasMany(JobPost::class, 'user_id', 'user_id');
    }
    
}