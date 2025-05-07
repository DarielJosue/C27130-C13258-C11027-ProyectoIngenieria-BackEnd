<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest_LocationType extends Model
{
    use HasFactory;

    protected $table = 'interest_location_types';
    public $timestamps = false;

    protected $fillable = [
        'interest_id',
        'location_type_id',
    ];

    public function interest()
    {
        return $this->belongsTo(Interest::class, 'interest_id');
    }
}