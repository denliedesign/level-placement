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

test('specialty eligibility falls back from jazz to ballet then tap', function () {
    $balletFallback = new Level([
        'jazz' => null,
        'ballet' => '4',
        'tap' => '1',
    ]);

    $tapFallback = new Level([
        'jazz' => null,
        'ballet' => null,
        'tap' => '6',
    ]);

    expect($balletFallback->specialtyClasses())->toContain('High Intermediate Hip Hop')
        ->and($balletFallback->specialtyClasses())->not->toContain('1st Intermediate Hip Hop')
        ->and($tapFallback->specialtyClasses())->toContain('Advanced Hip Hop')
        ->and($tapFallback->specialtyClasses())->toContain('Advanced Lyrical')
        ->and($tapFallback->specialtyClasses())->toContain('Advanced Modern');
});
