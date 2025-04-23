<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $primaryKey = 'InterestId';

    protected $fillable = [
        'UserId',
        'Visibility',
        'StartDate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }

    public function jobPositions()
    {
        return $this->belongsToMany(JobPosition::class, 'Interest_JobPosition', 'InterestId', 'JobPositionId');
    }
    public function jobTypes()
    {
        return $this->belongsToMany(JobType::class, 'Interest_JobType', 'InterestId', 'JobTypeId');
    }
    public function locationTypes()
    {
        return $this->belongsToMany(LocationType::class, 'Interest_LocationType', 'InterestId', 'LocationTypeId');
    }
    public function preferredLocations()
    {
        return $this->belongsToMany(PreferredLocation::class, 'Interest_PreferredLocation', 'InterestId', 'PreferredLocationId');
    }
}