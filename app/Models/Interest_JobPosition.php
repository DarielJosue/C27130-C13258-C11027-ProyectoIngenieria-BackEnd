<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest_JobPosition extends Model
{
    protected $table = 'Interest_Positions';
    public $timestamps = false;

    protected $fillable = [
        'InterestId',
        'PositionId'
    ];
}
