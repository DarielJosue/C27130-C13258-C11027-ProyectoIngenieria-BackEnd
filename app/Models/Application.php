<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';
    protected $primaryKey = 'application_id';

    protected $fillable = [
        'user_id',
        'job_post_id',
        'cv_id',
        'application_date',
        'message',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id', 'job_post_id');
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'cv_id', 'cv_id');
    }
}