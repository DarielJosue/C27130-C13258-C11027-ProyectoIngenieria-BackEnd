<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest_LocationType extends Model
{
    use HasFactory;
    protected $table = 'Interest_LocationTypes';
    public $timestamps = false;

    protected $fillable = [
        'InterestId',
        'LocationTypeId'
    ];

    public function interest()
    {
        return $this->belongsTo(Interest::class, 'InterestId');
    }
}
