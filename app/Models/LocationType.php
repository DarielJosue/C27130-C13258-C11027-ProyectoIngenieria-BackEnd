<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocationType extends Model
{
    use HasFactory;
    protected $table = 'location_types';
    protected $primaryKey = 'location_type_id';

    protected $fillable = [
        'location_type_name'
    ];

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_location_type', 'location_type_id', 'interest_id');
    }
}