<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest_JobPosition extends Model
{
    protected $table = 'interest_positions';
    public $timestamps = false;

    protected $fillable = [
        'interest_id',
        'position_id',
    ];
}