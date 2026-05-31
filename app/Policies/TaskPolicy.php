<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    private function role(User $user, Task $task): ?string
    {
        return $user->teams()
                    ->where('teams.id', $task->team_id)
                    ->first()
                    ?->pivot
                    ->role;
    }

    public function view(User $user, Task $task): bool
    {
        return $this->role($user, $task) !== null;
    }

    public function create(User $user): bool
    {
        return true; // verificato a livello di team nel controller
    }

    public function update(User $user, Task $task): bool
    {
        $role = $this->role($user, $task);

        // owner e admin possono aggiornare qualsiasi task
        // member solo i task assegnati a sé
        return match($role) {
            'owner', 'admin' => true,
            'member'         => $task->assigned_to === $user->id,
            default          => false,
        };
    }

    public function delete(User $user, Task $task): bool
    {
        $role = $this->role($user, $task);

        // solo owner e admin possono eliminare
        return in_array($role, ['owner', 'admin']);
    }
}