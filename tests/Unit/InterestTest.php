<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Interest;
use App\Models\JobPosition;
use App\Models\JobType;
use App\Models\LocationType;
use App\Models\PreferredLocation;


class InterestTest extends TestCase
{
     use RefreshDatabase;

    protected $jobPositions;
    protected $jobTypes;
    protected $locationTypes;
    protected $preferredLocations;

    protected function setUp(): void
    {
        parent::setUp();

        
        $this->jobPositions = JobPosition::factory()->count(5)->create();
        $this->jobTypes = JobType::factory()->count(4)->create();
        $this->locationTypes = LocationType::factory()->count(3)->create();
        $this->preferredLocations = PreferredLocation::factory()->count(6)->create();
    }

    /** 
     * 1. Un invitado (no autenticado) no debe poder acceder a GET ni a PUT
     */
    public function test_guest_cannot_access_endpoints()
    {
        $this->getJson('/profile/interests')
            ->assertStatus(401);

        $dummyPayload = [
            'visibility' => 'public',
            'start_date' => now()->toDateString(),
            'job_position_ids' => [$this->jobPositions[0]->job_position_id],
            'job_type_ids' => [$this->jobTypes[0]->job_type_id],
            'location_type_ids' => [$this->locationTypes[0]->location_type_id],
            'preferred_location_ids' => [$this->preferredLocations[0]->preferred_location_id],
        ];
        $this->putJson('/profile/interests', $dummyPayload)
            ->assertStatus(401);
    }

    /**
     * 2. Crear un "Interest" nuevo para un usuario que aún no tiene ninguno
     */
    public function test_user_can_create_interest_with_valid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $payload = [
            'visibility' => 'public',
            'start_date' => now()->toDateString(),
            'job_position_ids' => [
                $this->jobPositions[0]->job_position_id,
                $this->jobPositions[1]->job_position_id,
            ],
            'job_type_ids' => [
                $this->jobTypes[0]->job_type_id,
                $this->jobTypes[1]->job_type_id,
            ],
            'location_type_ids' => [
                $this->locationTypes[0]->location_type_id,
            ],
            'preferred_location_ids' => [
                $this->preferredLocations[0]->preferred_location_id,
                $this->preferredLocations[1]->preferred_location_id,
            ],
        ];

        $response = $this->putJson('/profile/interests', $payload);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'interest' => [
                    'interest_id',
                    'user_id',
                    'visibility',
                    'start_date',
                    'job_positions',
                    'job_types',
                    'location_types',
                    'preferred_locations',
                ],
            ]);

        $this->assertDatabaseHas('interests', [
            'user_id' => $user->id,
            'visibility' => 'public',
            'start_date' => now()->toDateString(),
        ]);

        $interestId = Interest::where('user_id', $user->id)->first()->interest_id;

        $this->assertDatabaseCount('interest_job_positions', 2);
        $this->assertDatabaseHas('interest_job_positions', [
            'interest_id' => $interestId,
            'job_position_id' => $this->jobPositions[0]->job_position_id,
        ]);
        $this->assertDatabaseHas('interest_job_positions', [
            'interest_id' => $interestId,
            'job_position_id' => $this->jobPositions[1]->job_position_id,
        ]);

        $this->assertDatabaseCount('interest_job_types', 2);
        $this->assertDatabaseHas('interest_job_types', [
            'interest_id' => $interestId,
            'job_type_id' => $this->jobTypes[0]->job_type_id,
        ]);
        $this->assertDatabaseHas('interest_job_types', [
            'interest_id' => $interestId,
            'job_type_id' => $this->jobTypes[1]->job_type_id,
        ]);

        $this->assertDatabaseCount('interest_location_types', 1);
        $this->assertDatabaseHas('interest_location_types', [
            'interest_id' => $interestId,
            'location_type_id' => $this->locationTypes[0]->location_type_id,
        ]);

        $this->assertDatabaseCount('interest_preferred_locations', 2);
        $this->assertDatabaseHas('interest_preferred_locations', [
            'interest_id' => $interestId,
            'preferred_location_id' => $this->preferredLocations[0]->preferred_location_id,
        ]);
        $this->assertDatabaseHas('interest_preferred_locations', [
            'interest_id' => $interestId,
            'preferred_location_id' => $this->preferredLocations[1]->preferred_location_id,
        ]);
    }

    /**
     * 3. Actualizar un “Interest” existente sin cambiar nada en los pivotes.
     */
    public function test_user_can_update_interest_without_changing_pivots()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $interest = Interest::factory()
            ->for($user, 'user')
            ->create([
                'visibility' => 'private',
                'start_date' => now()->subDays(10)->toDateString(),
            ]);

        $initialJobPosIds = [
            $this->jobPositions[0]->job_position_id,
            $this->jobPositions[1]->job_position_id,
        ];
        $initialJobTypeIds = [
            $this->jobTypes[0]->job_type_id,
            $this->jobTypes[1]->job_type_id,
        ];
        $initialLocationTypeIds = [
            $this->locationTypes[0]->location_type_id,
        ];
        $initialPrefLocIds = [
            $this->preferredLocations[0]->preferred_location_id,
            $this->preferredLocations[1]->preferred_location_id,
        ];

        $interest->jobPositions()->sync($initialJobPosIds);
        $interest->jobTypes()->sync($initialJobTypeIds);
        $interest->locationTypes()->sync($initialLocationTypeIds);
        $interest->preferredLocations()->sync($initialPrefLocIds);

        $this->assertDatabaseCount('interest_job_positions', count($initialJobPosIds));
        $this->assertDatabaseCount('interest_job_types', count($initialJobTypeIds));
        $this->assertDatabaseCount('interest_location_types', count($initialLocationTypeIds));
        $this->assertDatabaseCount('interest_preferred_locations', count($initialPrefLocIds));

        $payload = [
            'visibility' => 'public',          // cambio
            'start_date' => $interest->start_date, // igual
            'job_position_ids' => $initialJobPosIds,
            'job_type_ids' => $initialJobTypeIds,
            'location_type_ids' => $initialLocationTypeIds,
            'preferred_location_ids' => $initialPrefLocIds,
        ];

        $response = $this->putJson('/profile/interests', $payload);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'visibility' => 'public',
                'start_date' => $interest->start_date,
            ]);

        $this->assertDatabaseHas('interests', [
            'interest_id' => $interest->interest_id,
            'visibility' => 'public',
        ]);

      
        $this->assertDatabaseCount('interest_job_positions', count($initialJobPosIds));
        $this->assertDatabaseCount('interest_job_types', count($initialJobTypeIds));
        $this->assertDatabaseCount('interest_location_types', count($initialLocationTypeIds));
        $this->assertDatabaseCount('interest_preferred_locations', count($initialPrefLocIds));

        foreach ($initialJobPosIds as $jpid) {
            $this->assertDatabaseHas('interest_job_positions', [
                'interest_id' => $interest->interest_id,
                'job_position_id' => $jpid,
            ]);
        }
        foreach ($initialJobTypeIds as $jtid) {
            $this->assertDatabaseHas('interest_job_types', [
                'interest_id' => $interest->interest_id,
                'job_type_id' => $jtid,
            ]);
        }
        foreach ($initialLocationTypeIds as $ltid) {
            $this->assertDatabaseHas('interest_location_types', [
                'interest_id' => $interest->interest_id,
                'location_type_id' => $ltid,
            ]);
        }
        foreach ($initialPrefLocIds as $plid) {
            $this->assertDatabaseHas('interest_preferred_locations', [
                'interest_id' => $interest->interest_id,
                'preferred_location_id' => $plid,
            ]);
        }
    }

    /**
     * 4. Actualizar un “Interest” existente, removiendo algunos pivotes y agregando otros
     */
    public function test_user_can_update_interest_and_change_pivots()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $interest = Interest::factory()
            ->for($user, 'user')
            ->create([
                'visibility' => 'private',
                'start_date' => now()->subDays(15)->toDateString(),
            ]);

        $initialJobPosIds = [
            $this->jobPositions[0]->job_position_id,
            $this->jobPositions[1]->job_position_id,
            $this->jobPositions[2]->job_position_id,
        ];
        $initialJobTypeIds = [
            $this->jobTypes[0]->job_type_id,
            $this->jobTypes[1]->job_type_id,
        ];
        $initialLocationTypeIds = [
            $this->locationTypes[0]->location_type_id,
            $this->locationTypes[1]->location_type_id,
        ];
        $initialPrefLocIds = [
            $this->preferredLocations[0]->preferred_location_id,
            $this->preferredLocations[1]->preferred_location_id,
        ];

        $interest->jobPositions()->sync($initialJobPosIds);
        $interest->jobTypes()->sync($initialJobTypeIds);
        $interest->locationTypes()->sync($initialLocationTypeIds);
        $interest->preferredLocations()->sync($initialPrefLocIds);

        $newJobPosIds = [
            $this->jobPositions[1]->job_position_id, 
            $this->jobPositions[3]->job_position_id, 
        ];
        $newJobTypeIds = [
            $this->jobTypes[2]->job_type_id,         
        ];
        $newLocationTypeIds = [
            $this->locationTypes[2]->location_type_id, // nuevo
        ];
        $newPrefLocIds = [
            $this->preferredLocations[2]->preferred_location_id, // nuevo
            $this->preferredLocations[0]->preferred_location_id, // permanece
        ];

        $payload = [
            'visibility' => 'private', // mantengo igual
            'start_date' => $interest->start_date,
            'job_position_ids' => $newJobPosIds,
            'job_type_ids' => $newJobTypeIds,
            'location_type_ids' => $newLocationTypeIds,
            'preferred_location_ids' => $newPrefLocIds,
        ];

        $response = $this->putJson('/profile/interests', $payload);
        $response->assertStatus(200);

        $interestId = $interest->interest_id;

        // 4.3) Verificar que los IDs eliminados hayan desaparecido y los nuevos existan

        // JOB POSITIONS:
        // - Debe ETAR: ID[1], ID[3]
        $this->assertDatabaseCount('interest_job_positions', count($newJobPosIds));
        $this->assertDatabaseHas('interest_job_positions', [
            'interest_id' => $interestId,
            'job_position_id' => $this->jobPositions[1]->job_position_id,
        ]);
        $this->assertDatabaseHas('interest_job_positions', [
            'interest_id' => $interestId,
            'job_position_id' => $this->jobPositions[3]->job_position_id,
        ]);
        // Verificar que los antiguos (0,2) ya no estén
        $this->assertDatabaseMissing('interest_job_positions', [
            'interest_id' => $interestId,
            'job_position_id' => $this->jobPositions[0]->job_position_id,
        ]);
        $this->assertDatabaseMissing('interest_job_positions', [
            'interest_id' => $interestId,
            'job_position_id' => $this->jobPositions[2]->job_position_id,
        ]);

        // JOB TYPES:
        $this->assertDatabaseCount('interest_job_types', count($newJobTypeIds));
        $this->assertDatabaseHas('interest_job_types', [
            'interest_id' => $interestId,
            'job_type_id' => $this->jobTypes[2]->job_type_id,
        ]);
        $this->assertDatabaseMissing('interest_job_types', [
            'interest_id' => $interestId,
            'job_type_id' => $this->jobTypes[0]->job_type_id,
        ]);
        $this->assertDatabaseMissing('interest_job_types', [
            'interest_id' => $interestId,
            'job_type_id' => $this->jobTypes[1]->job_type_id,
        ]);

        // LOCATION TYPES:
        $this->assertDatabaseCount('interest_location_types', count($newLocationTypeIds));
        $this->assertDatabaseHas('interest_location_types', [
            'interest_id' => $interestId,
            'location_type_id' => $this->locationTypes[2]->location_type_id,
        ]);
        $this->assertDatabaseMissing('interest_location_types', [
            'interest_id' => $interestId,
            'location_type_id' => $this->locationTypes[0]->location_type_id,
        ]);
        $this->assertDatabaseMissing('interest_location_types', [
            'interest_id' => $interestId,
            'location_type_id' => $this->locationTypes[1]->location_type_id,
        ]);

        // PREFERRED LOCATIONS:
        $this->assertDatabaseCount('interest_preferred_locations', count($newPrefLocIds));
        $this->assertDatabaseHas('interest_preferred_locations', [
            'interest_id' => $interestId,
            'preferred_location_id' => $this->preferredLocations[2]->preferred_location_id,
        ]);
        $this->assertDatabaseHas('interest_preferred_locations', [
            'interest_id' => $interestId,
            'preferred_location_id' => $this->preferredLocations[0]->preferred_location_id,
        ]);
        $this->assertDatabaseMissing('interest_preferred_locations', [
            'interest_id' => $interestId,
            'preferred_location_id' => $this->preferredLocations[1]->preferred_location_id,
        ]);
    }

    /**
     * 5. Validación falla cuando se envía un ID que no existe en catálogo
     */
    public function test_validation_fails_for_invalid_ids()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        // Ids que sí existen:
        $validJobPosId = $this->jobPositions[0]->job_position_id;
        $validJobTypeId = $this->jobTypes[0]->job_type_id;
        $validLocationTypeId = $this->locationTypes[0]->location_type_id;
        $validPrefLocId = $this->preferredLocations[0]->preferred_location_id;

        // IDs no existentes (suponemos que 999 no existe)
        $invalidJobPosId = 999;
        $invalidJobTypeId = 888;
        $invalidLocationTypeId = 777;
        $invalidPrefLocId = 666;

        $payload = [
            'visibility' => 'public',
            'start_date' => now()->toDateString(),
            'job_position_ids' => [$validJobPosId, $invalidJobPosId],
            'job_type_ids' => [$validJobTypeId, $invalidJobTypeId],
            'location_type_ids' => [$validLocationTypeId, $invalidLocationTypeId],
            'preferred_location_ids' => [$validPrefLocId, $invalidPrefLocId],
        ];

        $this->withoutExceptionHandling();
        $response = $this->putJson('/profile/interests', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'job_position_ids.1',
                'job_type_ids.1',
                'location_type_ids.1',
                'preferred_location_ids.1',
            ]);

        // Verificar que NO se haya creado nada en 'interests'
        $this->assertDatabaseCount('interests', 0);
        $this->assertDatabaseCount('interest_job_positions', 0);
        $this->assertDatabaseCount('interest_job_types', 0);
        $this->assertDatabaseCount('interest_location_types', 0);
        $this->assertDatabaseCount('interest_preferred_locations', 0);
    }

    /**
     * 6. (Opcional) Intentar eliminar un Interest y verificar el cascado en pivotes
     *    — SOLO SI tu aplicación expone un DELETE /profile/interests
     */
    public function test_user_can_delete_interest_and_cascade_pivots()
    {
        // Si no tienes endpoint DELETE, omite este test.
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $interest = Interest::factory()
            ->for($user, 'user')
            ->create();

        // Adjuntar pivotes para luego verificar cascada
        $interest->jobPositions()->sync([
            $this->jobPositions[0]->job_position_id,
            $this->jobPositions[1]->job_position_id,
        ]);
        $interest->jobTypes()->sync([
            $this->jobTypes[0]->job_type_id,
        ]);
        $interest->locationTypes()->sync([
            $this->locationTypes[0]->location_type_id,
        ]);
        $interest->preferredLocations()->sync([
            $this->preferredLocations[0]->preferred_location_id,
        ]);

        // Verificar que existan filas en cada pivote
        $this->assertDatabaseCount('interest_job_positions', 2);
        $this->assertDatabaseCount('interest_job_types', 1);
        $this->assertDatabaseCount('interest_location_types', 1);
        $this->assertDatabaseCount('interest_preferred_locations', 1);

        // Invocar DELETE (ajusta ruta si es diferente)
        $this->deleteJson('/profile/interests')
            ->assertStatus(200);

        // Verificar que Interest ya no exista
        $this->assertDatabaseMissing('interests', [
            'interest_id' => $interest->interest_id,
        ]);

        // Verificar que los pivotes se borraron (onDelete cascade)
        $this->assertDatabaseCount('interest_job_positions', 0);
        $this->assertDatabaseCount('interest_job_types', 0);
        $this->assertDatabaseCount('interest_location_types', 0);
        $this->assertDatabaseCount('interest_preferred_locations', 0);
    }
}