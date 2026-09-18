<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_list_id' => TaskList::factory(),
            'user_id' => null,
            'email' => fake()->safeEmail(),
            'status' => Invitation::PENDING,
        ];
    }

    /**
     * Undangan yang ditujukan ke user terdaftar tertentu.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    }
}
