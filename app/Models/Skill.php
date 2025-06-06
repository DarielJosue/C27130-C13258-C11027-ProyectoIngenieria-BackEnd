<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $primaryKey = 'skill_id'; 

    protected $fillable = [
        'skill',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'skill_user', 'skill_id', 'user_id'); 
    }
}