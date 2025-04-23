<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    protected $primaryKey = 'JobPostId';

    protected $fillable = [
        'CompanyId',
        'Title',
        'Description',
        'Requirements',
        'PublishDate',
        'Salary',
        'Location',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'CompanyId');
    }
}
