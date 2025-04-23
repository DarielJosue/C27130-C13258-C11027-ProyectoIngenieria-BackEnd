<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill_User extends Model
{
    use HasFactory;

    protected $table = 'Skill_User';

    protected $fillable = [
        'SkillId',
        'UserId',
    ];

    public function skill()
    {
        return $this->belongsTo(Skill::class, 'SkillId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }
}