<?php

namespace App\Models;

use App\Enums\TaskPriority;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'description', 'priority', 'due_date'])]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'priority' => TaskPriority::class,
            'due_date' => 'date',
            'completed_at' => 'datetime',
            'is_completed' => 'boolean',
        ];
    }

    public function taskList(): BelongsTo
    {
        return $this->belongsTo(TaskList::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)->orderBy('position');
    }

    public function scopeCompleted(Builder $query): void
    {
        $query->where('is_completed', true);
    }

    public function scopeIncomplete(Builder $query): void
    {
        $query->where('is_completed', false);
    }

    public function scopeOverdue(Builder $query): void
    {
        $query->where('is_completed', false)->whereDate('due_date', '<', today());
    }

    public function scopeDueSoon(Builder $query): void
    {
        $query->where('is_completed', false)
            ->whereDate('due_date', '>=', today())
            ->whereDate('due_date', '<=', today()->addDays(config('tasks.due_soon_days')));
    }

    public function scopeSortByPriority(Builder $query, string $direction = 'asc'): void
    {
        $case = "CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END";

        $query->orderByRaw($direction === 'desc' ? "{$case} DESC" : $case);
    }

    public function scopeSortByDueDate(Builder $query, string $direction = 'asc'): void
    {
        $query->orderByRaw('due_date IS NULL')
            ->orderBy('due_date', $direction);
    }

    public function isOverdue(): bool
    {
        return ! $this->is_completed && $this->due_date !== null && $this->due_date->isPast();
    }

    public function isDueSoon(): bool
    {
        if ($this->is_completed || $this->due_date === null) {
            return false;
        }

        return $this->due_date->between(today(), today()->addDays(config('tasks.due_soon_days')));
    }

    public function dueStatus(): ?string
    {
        return match (true) {
            $this->isOverdue() => 'overdue',
            $this->isDueSoon() => 'due_soon',
            default => null,
        };
    }
}
