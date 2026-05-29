<?php

use App\Imports\LevelImport;
use Illuminate\Validation\ValidationException;

test('level import maps optional teacher notes', function () {
    $level = (new LevelImport())->model([
        'Avery',
        'Example',
        'parent@example.com',
        '3',
        '4',
        '',
        '',
        '2',
        'Try summer ballet.',
        'Strong musicality.',
    ]);

    expect($level->tap)->toBe('0')
        ->and($level->pointe)->toBe('0')
        ->and($level->teacher_recommendation)->toBe('Try summer ballet.')
        ->and($level->teacher_comments)->toBe('Strong musicality.');
});

test('level import ignores blank rows and extra blank columns', function () {
    $import = new LevelImport();

    expect($import->model(['', '', '', '', '', '', '', '', '', '', '', '']))->toBeNull();

    $level = $import->model([
        'Avery',
        'Example',
        'parent@example.com',
        '3',
        '4',
        '',
        '',
        '2',
        '',
        '',
        '',
        '',
    ]);

    expect($level->first_name)->toBe('Avery')
        ->and($level->email)->toBe('parent@example.com')
        ->and($level->teacher_recommendation)->toBeNull()
        ->and($level->teacher_comments)->toBeNull();
});

test('level import ignores a header row', function () {
    expect((new LevelImport())->model([
        'First Name',
        'Last Name',
        'Email',
        'Ballet',
        'Jazz',
    ]))->toBeNull();
});

test('level import reports row specific validation errors', function () {
    (new LevelImport())->model([
        'Avery',
        'Example',
        'not-an-email',
    ]);
})->throws(ValidationException::class, 'Row 2: The email must be a valid email address.');
