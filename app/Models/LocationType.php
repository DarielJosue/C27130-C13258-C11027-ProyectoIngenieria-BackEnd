<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationType extends Model
{
    protected $table = 'locationTypes';
    protected $primaryKey = 'LocationTypeId';

    protected $fillable = [
        'LocationTypeName'
    ];

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'Interest_LocationType', 'LocationTypeId', 'InterestId');
    }
}
