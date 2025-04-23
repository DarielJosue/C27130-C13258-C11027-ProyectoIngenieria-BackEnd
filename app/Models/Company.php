<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'CompanyId';

    protected $fillable = [
        'CompanyName',
        'Description',
        'Phone',
        'Location',
        'Website',
        'CompanySize',
        'Specialties',
        'RegisterDate',
    ];

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class, 'CompanyId');
    }

    public function companyUsers()
    {
        return $this->hasMany(CompanyUser::class, 'CompanyId');
    }
}
