<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'company_id';

    protected $fillable = [
        'company_name',
        'description',
        'phone',
        'location',
        'website',
        'company_size',
        'specialties',
        'register_date',
    ];

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class, 'company_id', 'company_id');
    }

    public function companyUsers()
    {
        return $this->hasMany(CompanyUser::class, 'company_id', 'company_id');
    }
}