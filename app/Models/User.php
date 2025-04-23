<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = 'UserId';
    public $timestamps = false;

    protected $fillable = [
        'Name',
        'LastName',
        'Password',
        'RegistartionDate',
    ];

    public function emails()
    {
        return $this->hasMany(Email::class, 'UserId');

    }
    public function profile()
    {
        return $this->hasOne(Profile::class, 'UserId');
    }
}
