<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $primaryKey = 'SkillId';

    protected $fillable = [
        'Skill',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'Skill_User', 'SkillId', 'UserId');
    }
}
