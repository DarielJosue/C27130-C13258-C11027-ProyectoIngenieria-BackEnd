<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobType extends Model
{
    use HasFactory;
    protected $primaryKey = 'job_type_id';

    protected $fillable = [
        'job_type_name'
    ];

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_job_type', 'job_type_id', 'interest_id');
    }
}