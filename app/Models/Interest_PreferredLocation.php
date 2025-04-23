<?php

namespace App\Models;
use illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest_PreferredLocation extends Model
{
    use HasFactory;

    protected $table = 'Interests_PreferredLocation';
    protected $fillable = [
        'InterestId',
        'PreferredLocationId',
    ];

    public function interest()
    {
        return $this->belongsTo(Interest::class, 'InterestId');
    }

    public function preferredLocation()
    {
        return $this->belongsTo(PreferredLocation::class, 'PreferredLocationId');
    }
}
