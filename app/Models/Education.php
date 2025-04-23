<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $primaryKey = 'EducationId';

    protected $fillable = [
        'UserId',
        'Institution',
        'Degree',
        'FieldOfStudy',
        'StartDate',
        'EndDate',
        'Activities',
        'Description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }
}
