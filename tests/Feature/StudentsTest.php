<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StudentsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'account_id' => Account::create(['name' => 'Acme Corporation'])->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'owner' => true,
        ]);

        $organization = $this->user->account->organizations()->create(['name' => 'Example Organization Inc.']);

        $this->user->account->students()->createMany([
            [
                'organization_id' => $organization->id,
                'first_name' => 'Martin',
                'middle_name' => 'James',
                'last_name' => 'Abbott',
                'email' => 'martin.abbott@example.com',
                'phone' => '555-111-2222',
                'address' => '330 Glenda Shore',
                'city' => 'Murphyland',
                'region' => 'Tennessee',
                'country' => 'US',
                'postal_code' => '57851',
            ], [
                'organization_id' => $organization->id,
                'first_name' => 'Lynn',
                'middle_name' => 'Marie',
                'last_name' => 'Kub',
                'email' => 'lynn.kub@example.com',
                'phone' => '555-333-4444',
                'address' => '199 Connelly Turnpike',
                'city' => 'Woodstock',
                'region' => 'Colorado',
                'country' => 'US',
                'postal_code' => '11623',
            ],
        ]);
    }

    public function test_can_view_students(): void
    {
        $this->actingAs($this->user)
            ->get('/students')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Students/Index')
                ->has('students.data', 2)
                ->has('students.data.0', fn (Assert $assert) => $assert
                    ->has('id')
                    ->where('name', 'Martin James Abbott')
                    ->where('phone', '555-111-2222')
                    ->where('city', 'Murphyland')
                    ->where('deleted_at', null)
                )
                ->has('students.data.1', fn (Assert $assert) => $assert
                    ->has('id')
                    ->where('name', 'Lynn Marie Kub')
                    ->where('phone', '555-333-4444')
                    ->where('city', 'Woodstock')
                    ->where('deleted_at', null)
                )
            );
    }

    public function test_can_search_for_students(): void
    {
        $this->actingAs($this->user)
            ->get('/students?search=Martin')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Students/Index')
                ->where('filters.search', 'Martin')
                ->has('students.data', 1)
                ->has('students.data.0', fn (Assert $assert) => $assert
                    ->has('id')
                    ->where('name', 'Martin James Abbott')
                    ->where('phone', '555-111-2222')
                    ->where('city', 'Murphyland')
                    ->where('deleted_at', null)
                )
            );
    }

    public function test_cannot_view_deleted_students(): void
    {
        $this->user->account->students()->firstWhere('first_name', 'Martin')->delete();

        $this->actingAs($this->user)
            ->get('/students')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Students/Index')
                ->has('students.data', 1)
                ->where('students.data.0.name', 'Lynn Marie Kub')
            );
    }

    public function test_can_filter_to_view_deleted_students(): void
    {
        $this->user->account->students()->firstWhere('first_name', 'Martin')->delete();

        $this->actingAs($this->user)
            ->get('/students?trashed=with')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Students/Index')
                ->has('students.data', 2)
                ->where('students.data.0.name', 'Martin James Abbott')
                ->where('students.data.1.name', 'Lynn Marie Kub')
            );
    }
} 