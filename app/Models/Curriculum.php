<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $table = 'curricula';
    protected $primaryKey = 'CvId';

    protected $fillable = [
        'UserId',
        'FilePath',
        'UploadDate',
        'Description',
        'IsDefault',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }
}