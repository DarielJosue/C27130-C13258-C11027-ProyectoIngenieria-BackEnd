<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill_User extends Model
{
    use HasFactory;

    protected $table = 'skill_user';

    protected $fillable = [
        'skill_id',
        'user_id',
    ];

    public function skill()
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}