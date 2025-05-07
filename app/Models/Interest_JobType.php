<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest_JobType extends Model
{
    use HasFactory;

    protected $table = 'interest_job_types';
    public $timestamps = false;

    protected $fillable = [
        'interest_id',
        'job_type_id',
    ];
}