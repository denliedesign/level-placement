<?php

namespace App\Imports;

use App\Models\DanceClass;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithFormatData;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class DanceClassesImport implements ToModel, WithFormatData
{
    public function model(array $row): ?DanceClass
    {
        $dayOfWeek = $this->value($row, 0);
        $time = $this->value($row, 1);
        $style = $this->value($row, 2);
        $level = $this->levelValue($row, 3);

        if ($this->isHeaderRow($dayOfWeek, $time, $style, $level)) {
            return null;
        }

        if ($dayOfWeek === null || $time === null || $style === null || $level === null) {
            return null;
        }

        return new DanceClass([
            'day_of_week' => $dayOfWeek,
            'time' => $time,
            'name' => DanceClass::classNameFor($style, $level),
            'age_requirement' => null,
            'dance_style' => $style,
            'level' => $level,
        ]);
    }

    private function value(array $row, int $index): ?string
    {
        $value = trim((string) ($row[$index] ?? ''));

        return $value === '' ? null : $value;
    }

    private function levelValue(array $row, int $index): ?string
    {
        $value = $this->value($row, $index);

        if ($value === null) {
            return null;
        }

        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/\d{2,4}$/', $value, $matches) === 1) {
            return $matches[1].'/'.$matches[2];
        }

        if (preg_match('/^\d{5}(?:\.0+)?$/', $value) === 1) {
            return ExcelDate::excelToDateTimeObject((float) $value)->format('n/j');
        }

        return $value;
    }

    private function isHeaderRow(?string $dayOfWeek, ?string $time, ?string $style, ?string $level): bool
    {
        $headerValues = [
            $this->headerValue($dayOfWeek),
            $this->headerValue($time),
            $this->headerValue($style),
            $this->headerValue($level),
        ];

        return in_array('day', $headerValues, true)
            && in_array('time', $headerValues, true)
            && in_array('style', $headerValues, true)
            && in_array('level', $headerValues, true);
    }

    private function headerValue(?string $value): string
    {
        $value = strtolower(trim((string) $value));
        $value = preg_replace('/[^a-z]+/', ' ', $value);
        $value = trim(preg_replace('/\s+/', ' ', $value));

        return match ($value) {
            'day', 'day of week', 'day of the week', 'weekday' => 'day',
            'time', 'class time' => 'time',
            'style', 'dance style', 'class style' => 'style',
            'level', 'class level', 'placement level' => 'level',
            default => $value,
        };
    }
}
