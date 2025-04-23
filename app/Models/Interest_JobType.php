<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest_JobType extends Model
{
    use HasFactory;
    protected $table = 'Interest_JobTypes';
    public $timestamps = false;

    protected $fillable = [
        'InterestId',
        'JobTypeId'
    ];
}
