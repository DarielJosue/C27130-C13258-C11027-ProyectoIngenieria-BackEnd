<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_post_id';

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'requirements',
        'publish_date',
        'salary',
        'location',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
    public function jobPosts()
    {
        return $this->belongsTo(JobPost::class, 'company_id', 'company_id');
    }
    public function applications()
    {
        return $this->hasMany(Application::class, 'job_post_id', 'job_post_id');
    }
}