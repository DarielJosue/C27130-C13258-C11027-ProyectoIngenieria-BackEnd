<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $primaryKey = 'PositionId';

    protected $fillable = [
        'PositionName'
    ];

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'Interest_JobPosition', 'JobPositionId', 'InterestId');
    }
}
