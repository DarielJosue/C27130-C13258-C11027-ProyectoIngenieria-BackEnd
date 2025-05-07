<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $primaryKey = 'interest_id';

    protected $fillable = [
        'user_id',
        'visibility',
        'start_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobPositions()
    {
        return $this->belongsToMany(JobPosition::class, 'interest_job_position', 'interest_id', 'job_position_id');
    }

    public function jobTypes()
    {
        return $this->belongsToMany(JobType::class, 'interest_job_type', 'interest_id', 'job_type_id');
    }

    public function locationTypes()
    {
        return $this->belongsToMany(LocationType::class, 'interest_location_type', 'interest_id', 'location_type_id');
    }

    public function preferredLocations()
    {
        return $this->belongsToMany(PreferredLocation::class, 'interest_preferred_location', 'interest_id', 'preferred_location_id');
    }
}