<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    private const STYLE_LABELS = [
        'ballet' => 'Ballet',
        'jazz' => 'Jazz',
        'tap' => 'Tap',
        'pointe' => 'Pointe',
        'acro' => 'Acro',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'ballet',
        'jazz',
        'tap',
        'pointe',
        'acro',
        'teacher_recommendation',
        'teacher_comments',
    ];

    public function fullName(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function placementEntries(): array
    {
        $entries = [];

        foreach (self::STYLE_LABELS as $attribute => $label) {
            $value = $this->{$attribute};

            if ($this->hasPlacementValue($value)) {
                $entries[] = [
                    'label' => $label,
                    'value' => $value,
                ];
            }
        }

        return $entries;
    }

    public function specialtyClasses(): array
    {
        $classes = [];
        $jazzLevel = trim((string) $this->jazz);

        if (is_numeric($jazzLevel)) {
            $jazzLevel = (int) $jazzLevel;

            if ($jazzLevel >= 1 && $jazzLevel <= 2) {
                $classes = [
                '1st Intermediate Modern',
                '1st Intermediate Lyrical',
                '1st Intermediate Hip Hop',
                '1st Intermediate Musical Theater',
                ];
            }

            if ($jazzLevel >= 3 && $jazzLevel <= 4) {
                $classes = [
                'High Intermediate Modern',
                'High Intermediate Lyrical',
                'High Intermediate Hip Hop',
                ];
            }

            if ($jazzLevel >= 5 && $jazzLevel <= 7) {
                $classes = [
                'Advanced Modern',
                'Advanced Lyrical',
                'Advanced Hip Hop',
                ];
            }
        }

        if ($this->strengthAndStretchPlacements() !== []) {
            $classes[] = 'Strength & Stretch';
        }

        return collect($classes)->unique()->values()->all();
    }

    public function eligibleClassNames(): array
    {
        return collect($this->eligibleClassPlacements())
            ->map(fn (array $placement) => DanceClass::classNameFor($placement['style'], $placement['level']))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function eligibleClassPlacements(): array
    {
        $placements = [];

        foreach ($this->placementEntries() as $placement) {
            $placements[] = [
                'style' => $placement['label'],
                'level' => $placement['value'],
            ];
        }

        foreach ($this->specialtyClassPlacements() as $placement) {
            $placements[] = $placement;
        }

        foreach ($this->strengthAndStretchPlacements() as $placement) {
            $placements[] = $placement;
        }

        return collect($placements)
            ->filter(fn (array $placement) => $this->hasTextValue($placement['style'] ?? '') && $this->hasTextValue($placement['level'] ?? ''))
            ->unique(fn (array $placement) => DanceClass::normalizeClassName($placement['style']).'|'.DanceClass::normalizeClassName($placement['level']))
            ->filter()
            ->values()
            ->all();
    }

    public function matchingDanceClasses(): Collection
    {
        return DanceClass::matchingPlacements($this->eligibleClassPlacements());
    }

    public function hasTeacherRecommendation(): bool
    {
        return $this->hasTextValue($this->teacher_recommendation);
    }

    public function hasTeacherComments(): bool
    {
        return $this->hasTextValue($this->teacher_comments);
    }

    private function hasPlacementValue(?string $value): bool
    {
        $value = trim((string) $value);

        return $value !== '' && $value !== '0';
    }

    private function specialtyClassPlacements(): array
    {
        return collect($this->specialtyClasses())
            ->map(function (string $className) {
                $normalizedClassName = DanceClass::normalizeClassName($className);

                foreach (['Musical Theater', 'Hip Hop', 'Modern', 'Lyrical'] as $style) {
                    $normalizedStyle = DanceClass::normalizeClassName($style);

                    if (str_ends_with($normalizedClassName, $normalizedStyle)) {
                        return [
                            'style' => $style,
                            'level' => trim(substr($className, 0, -strlen($style))),
                        ];
                    }
                }

                return null;
            })
            ->filter()
            ->values()
            ->all();
    }

    private function strengthAndStretchPlacements(): array
    {
        return collect($this->placementEntries())
            ->map(fn (array $placement) => trim((string) $placement['value']))
            ->filter(fn (string $level) => is_numeric($level) && (int) $level >= 1 && (int) $level <= 7)
            ->map(fn (string $level) => [
                'style' => 'Strength & Stretch',
                'level' => $level,
            ])
            ->values()
            ->all();
    }

    private function hasTextValue(?string $value): bool
    {
        return trim((string) $value) !== '';
    }
}
