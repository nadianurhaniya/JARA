<?php

namespace App\Enums;

enum TaskPriority: string
{
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';

    public function label(): string
    {
        return match ($this) {
            self::High => 'Tinggi',
            self::Medium => 'Sedang',
            self::Low => 'Rendah',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::High => 'bg-red-100 text-red-700',
            self::Medium => 'bg-amber-100 text-amber-700',
            self::Low => 'bg-emerald-100 text-emerald-700',
        };
    }
}
