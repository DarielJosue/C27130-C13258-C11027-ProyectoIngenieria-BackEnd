<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $primaryKey = 'position_id';

    protected $fillable = [
        'position_name'
    ];

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_job_position', 'job_position_id', 'interest_id');
    }
}