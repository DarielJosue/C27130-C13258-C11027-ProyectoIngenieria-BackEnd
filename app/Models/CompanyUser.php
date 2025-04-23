<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    use HasFactory;

    protected $primaryKey = 'CompanyUserId';

    protected $fillable = [
        'CompanyId',
        'Name',
        'Email',
        'Password',
        'Role',
        'Active',
        'RegisterDate',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'CompanyId');
    }
}
