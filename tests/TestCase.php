<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\JobPosition;
use App\Models\JobType;
use App\Models\LocationType;
use App\Models\PreferredLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        JobPosition::factory()->count(5)->create();
        JobType::factory()->count(4)->create();
        LocationType::factory()->count(3)->create();
        PreferredLocation::factory()->count(6)->create();
    }
}