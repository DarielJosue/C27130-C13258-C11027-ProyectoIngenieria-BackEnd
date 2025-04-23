<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreferredLocation extends Model
{
    protected $table = 'preferred_locations';
    protected $primaryKey = 'PreferredLocationId';


    protected $fillable = [
        'UserId',
        'LocationTypeId',
    ];

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'Interest_PreferredLocation', 'PreferredLocationId', 'InterestId');
    }
}