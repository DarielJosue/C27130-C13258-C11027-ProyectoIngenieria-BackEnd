<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authennticatable;

class CompanyUser extends Authennticatable
{
    use HasFactory, HasApiTokens;

    protected $primaryKey = 'company_user_id';

    protected $fillable = [
        'company_id',
        'name',
        'username',
        'lastname',
        'email',
        'password',
        'role',
        'active',
        'register_date',
    ];
    protected $hidden = [
        'password',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}