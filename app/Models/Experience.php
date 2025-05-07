<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $primaryKey = 'experience_id';

    protected $fillable = [
        'user_id',
        'employment_type',
        'company',
        'start_date',
        'end_date',
        'location',
        'location_type',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}