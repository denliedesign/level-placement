<?php

use App\Models\DanceClass;

test('pointe placement keys normalize common class level labels', function () {
    expect(DanceClass::placementKey('Pointe', '1'))->toBe('pointe|1')
        ->and(DanceClass::placementKey('Pointe', 'Pointe 1'))->toBe('pointe|1')
        ->and(DanceClass::placementKey('Point', 'Pointe 1'))->toBe('pointe|1')
        ->and(DanceClass::placementKey('Pre-Pointe', 'Pre'))->toBe('pointe|pre-pointe');
});

test('pointe combined class levels expand to searchable placement keys', function () {
    expect(DanceClass::levelOptions('Pre-Pointe/Pointe 1'))->toBe(['Pre-Pointe', '1'])
        ->and(DanceClass::placementKeysFor('Pointe', 'Pre-Pointe/Pointe 1'))->toBe([
            'pointe|pre-pointe',
            'pointe|1',
        ]);
});
