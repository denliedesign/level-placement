<?php

use App\Models\Level;

test('first intermediate jazz adds musical theater eligibility', function () {
    $level = new Level([
        'jazz' => '2',
    ]);

    expect($level->specialtyClasses())->toContain('1st Intermediate Musical Theater')
        ->and($level->eligibleClassPlacements())->toContain([
            'style' => 'Musical Theater',
            'level' => '1st Intermediate',
        ]);
});

test('strength and stretch eligibility follows numeric placement levels', function () {
    $level = new Level([
        'ballet' => '3',
        'jazz' => null,
        'tap' => '5',
        'pointe' => 'Pre',
    ]);

    expect($level->specialtyClasses())->toContain('Strength & Stretch')
        ->and($level->eligibleClassPlacements())->toContain([
            'style' => 'Strength & Stretch',
            'level' => '3',
        ])
        ->and($level->eligibleClassPlacements())->toContain([
            'style' => 'Strength & Stretch',
            'level' => '5',
        ])
        ->and($level->eligibleClassPlacements())->not->toContain([
            'style' => 'Strength & Stretch',
            'level' => 'Pre',
        ]);
});
