<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::firstOrCreate(
            ['email' => 'vito@nexahub.test'],
            [
                'name' => 'Vito Millozza',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $team = Team::firstOrCreate(
            ['slug' => 'nexahub-core'],
            [
                'name' => 'NexaHub Core',
                'owner_id' => $owner->id,
            ]
        );

        // Associa il proprietario al team senza duplicati
        $team->members()->syncWithoutDetaching([
            $owner->id => ['role' => 'owner'],
        ]);

        // Crea 4 membri
        $members = User::factory()->count(4)->create();

        foreach ($members as $member) {
            $team->members()->syncWithoutDetaching([
                $member->id => ['role' => 'member'],
            ]);
        }

        // Crea task solo se non ne esistono già
        if ($team->tasks()->count() === 0) {
            Task::factory()
                ->count(20)
                ->create([
                    'team_id' => $team->id,
                    'created_by' => $owner->id,
                ]);
        }
    }
}