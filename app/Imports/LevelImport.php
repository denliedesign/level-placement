<?php

namespace App\Imports;

use App\Models\Level;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class LevelImport implements ToModel, WithStartRow
{
    private int $rowNumber = 1;

    public function startRow(): int
    {
        return 1;
    }

    public function model(array $row): ?Level
    {
        $this->rowNumber++;

        if ($this->isBlankRow($row) || $this->isHeaderRow($row)) {
            return null;
        }

        $data = [
            'first_name' => $this->value($row, 0),
            'last_name' => $this->value($row, 1),
            'email' => $this->value($row, 2),
        ];

        $this->validateRequiredFields($data);

        return new Level([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'ballet' => $this->value($row, 3, zeroWhenEmpty: true),
            'jazz' => $this->value($row, 4, zeroWhenEmpty: true),
            'tap' => $this->value($row, 5, zeroWhenEmpty: true),
            'pointe' => $this->value($row, 6, zeroWhenEmpty: true),
            'acro' => $this->value($row, 7, zeroWhenEmpty: true),
            'teacher_recommendation' => $this->value($row, 8),
            'teacher_comments' => $this->value($row, 9),
        ]);
    }

    private function isBlankRow(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function isHeaderRow(array $row): bool
    {
        return strtolower((string) $this->value($row, 0)) === 'first name'
            && strtolower((string) $this->value($row, 1)) === 'last name'
            && strtolower((string) $this->value($row, 2)) === 'email';
    }

    private function validateRequiredFields(array $data): void
    {
        if ($data['first_name'] === null) {
            $this->throwRowError('The first name field is required.');
        }

        if ($data['last_name'] === null) {
            $this->throwRowError('The last name field is required.');
        }

        if ($data['email'] === null) {
            $this->throwRowError('The email field is required.');
        }

        if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->throwRowError('The email must be a valid email address.');
        }
    }

    private function throwRowError(string $message): void
    {
        throw ValidationException::withMessages([
            'file' => 'Row '.$this->rowNumber.': '.$message,
        ]);
    }

    private function value(array $row, int $index, bool $zeroWhenEmpty = false): ?string
    {
        $value = trim((string) ($row[$index] ?? ''));

        if ($value === '') {
            return $zeroWhenEmpty ? '0' : null;
        }

        return $value;
    }
}
