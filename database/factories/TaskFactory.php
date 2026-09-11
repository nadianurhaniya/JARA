<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
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
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'priority' => fake()->randomElement(TaskPriority::cases()),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'is_completed' => false,
            'position' => 0,
        ];
    }

    /**
     * Indicate that the task is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    /**
     * Indicate that the task's due date has already passed.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes): array => [
            'due_date' => now()->subDays(3),
            'is_completed' => false,
        ]);
    }

    /**
     * Indicate that the task's due date is coming up soon.
     */
    public function dueSoon(): static
    {
        return $this->state(fn (array $attributes): array => [
            'due_date' => now()->addDay(),
            'is_completed' => false,
        ]);
    }

    /**
     * Indicate that the task has high priority.
     */
    public function highPriority(): static
    {
        return $this->state(['priority' => TaskPriority::High]);
    }

    /**
     * Indicate that the task has medium priority.
     */
    public function mediumPriority(): static
    {
        return $this->state(['priority' => TaskPriority::Medium]);
    }

    /**
     * Indicate that the task has low priority.
     */
    public function lowPriority(): static
    {
        return $this->state(['priority' => TaskPriority::Low]);
    }
}
