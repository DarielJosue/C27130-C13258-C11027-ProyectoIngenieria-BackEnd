<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplication extends Model
{
    use HasFactory;

    protected $table = 'aplications';
    protected $primaryKey = 'ApplicationId';

    protected $fillable = [
        'UserId',
        'JobPostId',
        'CurriculumId',
        'ApplicationDate',
        'Message',
        'Status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'JobPostId');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'CurriculumId');
    }
}