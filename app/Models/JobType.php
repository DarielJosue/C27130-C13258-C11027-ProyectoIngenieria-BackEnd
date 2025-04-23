<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $primaryKey = 'JobTypeId';

    protected $fillable = [
        'JobTypeName'
    ];

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'Interest_JobType', 'JobTypeId', 'InterestId');
    }
}
