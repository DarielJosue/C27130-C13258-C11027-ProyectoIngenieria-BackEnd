<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $primaryKey = 'ExperienceId';

    protected $fillable = [
        'UserId',
        'EmploymentType',
        'Company',
        'StartDate',
        'EndDate',
        'Location',
        'LocationType',
        'Description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }
}
