<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest_PreferredLocation extends Model
{
    use HasFactory;

    protected $table = 'interests_preferred_location';

    protected $fillable = [
        'interest_id',
        'preferred_location_id',
    ];

    public function interest()
    {
        return $this->belongsTo(Interest::class, 'interest_id');
    }

    public function preferredLocation()
    {
        return $this->belongsTo(PreferredLocation::class, 'preferred_location_id');
    }
}