<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DanceClass extends Model
{
    protected $fillable = [
        'name',
        'age_requirement',
        'dance_style',
        'level',
        'day_of_week',
        'time',
    ];

    public static function matchingPlacementNames(array $placementNames): Collection
    {
        $normalizedNames = collect($placementNames)
            ->map(fn (string $name) => self::normalizeClassName($name))
            ->filter()
            ->unique()
            ->values();

        if ($normalizedNames->isEmpty()) {
            return new Collection();
        }

        return self::query()
            ->orderBy('name')
            ->orderBy('day_of_week')
            ->orderBy('time')
            ->get()
            ->filter(fn (self $danceClass) => $normalizedNames->contains(self::normalizeClassName($danceClass->name)))
            ->values();
    }

    public static function matchingPlacements(array $placements): Collection
    {
        $placementKeys = collect($placements)
            ->map(fn (array $placement) => self::placementKey($placement['style'] ?? null, $placement['level'] ?? null))
            ->filter()
            ->unique()
            ->values();

        if ($placementKeys->isEmpty()) {
            return new Collection();
        }

        return self::query()
            ->orderBy('dance_style')
            ->orderBy('level')
            ->orderBy('day_of_week')
            ->orderBy('time')
            ->get()
            ->filter(fn (self $danceClass) => collect(self::placementKeysFor($danceClass->dance_style, $danceClass->level))->intersect($placementKeys)->isNotEmpty())
            ->values();
    }

    public static function placementKeysFor(?string $style, ?string $level): array
    {
        return collect(self::levelOptions($level))
            ->map(fn (string $levelOption) => self::placementKey($style, $levelOption))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public static function placementKey(?string $style, ?string $level): string
    {
        $style = self::normalizePlacementStyle($style);
        $level = self::normalizePlacementLevel($style, $level);

        if ($style === '' || $level === '') {
            return '';
        }

        return $style.'|'.$level;
    }

    public static function levelOptions(?string $level): array
    {
        $level = trim((string) $level);

        if ($level === '') {
            return [];
        }

        $pointeLevelOptions = self::pointeLevelOptions($level);

        if ($pointeLevelOptions !== []) {
            return $pointeLevelOptions;
        }

        if (preg_match('/^(\d+)\s*(?:-|\x{2013})\s*(\d+)$/u', $level, $matches) === 1) {
            $start = (int) $matches[1];
            $end = (int) $matches[2];

            if ($start > $end) {
                [$start, $end] = [$end, $start];
            }

            if ($end - $start <= 12) {
                return array_map('strval', range($start, $end));
            }
        }

        if (preg_match('/^\d+([\s\/,]+\d+)+$/', $level) === 1) {
            return collect(preg_split('/[\s\/,]+/', $level))
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        return [$level];
    }

    private static function normalizePlacementStyle(?string $style): string
    {
        $style = self::normalizeClassName($style);

        return in_array($style, ['point', 'pointe', 'pre pointe', 'pre-pointe'], true)
            ? 'pointe'
            : $style;
    }

    private static function normalizePlacementLevel(string $normalizedStyle, ?string $level): string
    {
        if ($normalizedStyle === 'pointe') {
            return self::normalizeClassName(self::pointeLevelOptions($level)[0] ?? $level);
        }

        return self::normalizeClassName($level);
    }

    private static function pointeLevelOptions(?string $level): array
    {
        $level = trim((string) $level);

        if ($level === '') {
            return [];
        }

        if (str_contains($level, '/')) {
            return collect(preg_split('/\s*\/\s*/', $level))
                ->flatMap(fn (string $levelPart) => self::pointeLevelOptions($levelPart))
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        $normalizedLevel = self::normalizeClassName($level);
        $normalizedLevel = trim($normalizedLevel, " \t\n\r\0\x0B-");

        if (in_array($normalizedLevel, ['pre', 'pre pointe', 'pre-pointe'], true)) {
            return ['Pre-Pointe'];
        }

        if (preg_match('/^pointe\s*(\d+)$/', $normalizedLevel, $matches) === 1) {
            return [$matches[1]];
        }

        return [];
    }

    public static function normalizeClassName(?string $name): string
    {
        $name = strtolower(trim((string) $name));

        return preg_replace('/\s+/', ' ', $name);
    }

    public static function classNameFor(?string $style, ?string $level): string
    {
        $style = trim((string) $style);
        $level = trim((string) $level);

        if ($style === '') {
            return $level;
        }

        if ($level === '') {
            return $style;
        }

        $normalizedStyle = self::normalizeClassName($style);
        $normalizedLevel = self::normalizeClassName($level);

        if (str_contains($normalizedLevel, $normalizedStyle)) {
            return $level;
        }

        $displayLevel = match ($normalizedLevel) {
            '1st int', '1st intermediate' => '1st Intermediate',
            'high int', 'high intermediate' => 'High Intermediate',
            'adv', 'advanced' => 'Advanced',
            default => $level,
        };

        if (in_array(self::normalizeClassName($displayLevel), ['1st intermediate', 'high intermediate', 'advanced'], true)) {
            return $displayLevel.' '.$style;
        }

        return $style.' '.$level;
    }
}
