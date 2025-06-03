<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PreferredLocation extends Model
{
    use HasFactory;
    protected $table = 'preferred_locations';
    protected $primaryKey = 'preferred_location_id';

    protected $fillable = [
        'user_id',
        'location_type_id',
    ];

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_preferred_location', 'preferred_location_id', 'interest_id');
    }
}