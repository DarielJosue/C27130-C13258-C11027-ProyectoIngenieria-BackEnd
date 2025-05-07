<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    use HasFactory;

    protected $primaryKey = 'company_user_id';

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'role',
        'active',
        'register_date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}