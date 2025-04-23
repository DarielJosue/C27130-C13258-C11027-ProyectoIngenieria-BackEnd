<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $primaryKey = 'ProfileId';

    protected $fillable = [
        'UserId',
        'Biography',
        'PhoneNumber',
        'Address'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }
}