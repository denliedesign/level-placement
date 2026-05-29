<?php

use App\Imports\DanceClassesImport;

test('dance class import maps scheduler spreadsheet columns', function () {
    $danceClass = (new DanceClassesImport())->model([
        'Wednesday',
        '5:00pm - 6:00pm',
        'Jazz',
        '4',
    ]);

    expect($danceClass->day_of_week)->toBe('Wednesday')
        ->and($danceClass->time)->toBe('5:00pm - 6:00pm')
        ->and($danceClass->name)->toBe('Jazz 4')
        ->and($danceClass->age_requirement)->toBeNull()
        ->and($danceClass->dance_style)->toBe('Jazz')
        ->and($danceClass->level)->toBe('4');
});

test('dance class import builds specialty placement names from style and level', function () {
    $danceClass = (new DanceClassesImport())->model([
        'Wednesday',
        '7:45pm - 8:30pm',
        'Hip Hop',
        'High Intermediate',
    ]);

    expect($danceClass->name)->toBe('High Intermediate Hip Hop')
        ->and($danceClass->dance_style)->toBe('Hip Hop')
        ->and($danceClass->level)->toBe('High Intermediate');
});

test('dance class import skips the expected header row', function () {
    $danceClass = (new DanceClassesImport())->model([
        'Day of Week',
        'Class Time',
        'Dance Style',
        'Placement Level',
    ]);

    expect($danceClass)->toBeNull();
});

test('dance class import accepts combined numeric levels', function () {
    $acroClass = (new DanceClassesImport())->model([
        'Monday',
        '4:00pm - 5:00pm',
        'Acro',
        '1-2',
    ]);

    $jazzClass = (new DanceClassesImport())->model([
        'Tuesday',
        '5:00pm - 6:00pm',
        'Jazz',
        '5/6',
    ]);

    expect($acroClass->name)->toBe('Acro 1-2')
        ->and($acroClass->level)->toBe('1-2')
        ->and($jazzClass->name)->toBe('Jazz 5/6')
        ->and($jazzClass->level)->toBe('5/6');
});

test('dance class import preserves slash levels that spreadsheets converted to dates', function () {
    $formattedDateLevel = (new DanceClassesImport())->model([
        'Wednesday',
        '6:00pm - 7:00pm',
        'Jazz',
        '4/5/2026',
    ]);

    $rawDateSerialLevel = (new DanceClassesImport())->model([
        'Wednesday',
        '6:00pm - 7:00pm',
        'Jazz',
        '46117',
    ]);

    expect($formattedDateLevel->level)->toBe('4/5')
        ->and($formattedDateLevel->name)->toBe('Jazz 4/5')
        ->and($rawDateSerialLevel->level)->toBe('4/5')
        ->and($rawDateSerialLevel->name)->toBe('Jazz 4/5');
});
