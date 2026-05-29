<?php

use App\Models\Level;
use App\Models\DanceClass;
use App\Models\User;

test('parents see their placements as certificates', function () {
    $user = User::factory()->create([
        'email' => 'parent@example.com',
    ]);

    Level::create([
        'first_name' => 'Avery',
        'last_name' => 'Example',
        'email' => 'parent@example.com',
        'ballet' => '3',
        'jazz' => '4',
        'tap' => '2',
        'teacher_recommendation' => 'Try summer ballet and jazz technique.',
        'teacher_comments' => 'Avery worked hard all season.',
    ]);

    DanceClass::create([
        'name' => 'Jazz 4',
        'dance_style' => 'Jazz',
        'level' => '4',
        'day_of_week' => 'Wednesday',
        'time' => '5:00pm - 6:00pm',
    ]);

    DanceClass::create([
        'name' => 'Jazz 4',
        'dance_style' => 'Jazz',
        'level' => '4',
        'day_of_week' => 'Thursday',
        'time' => '6:30pm - 7:30pm',
    ]);

    DanceClass::create([
        'name' => 'High Intermediate Hip Hop',
        'dance_style' => 'Hip Hop',
        'level' => 'High Intermediate',
        'day_of_week' => 'Wednesday',
        'time' => '7:45pm - 8:30pm',
    ]);

    Level::create([
        'first_name' => 'Other',
        'last_name' => 'Dancer',
        'email' => 'other@example.com',
        'ballet' => '5',
    ]);

    $this->actingAs($user)
        ->get(route('levels.index'))
        ->assertOk()
        ->assertViewIs('levels.show')
        ->assertSee('Congratulations')
        ->assertSee('Avery Example')
        ->assertSee('Ballet')
        ->assertSee('Tap')
        ->assertSee('High Intermediate Modern')
        ->assertSee('High Intermediate Lyrical')
        ->assertSee('High Intermediate Hip Hop')
        ->assertSee('Exact Class Options')
        ->assertSee('Jazz')
        ->assertSee('4')
        ->assertSee('Wednesday')
        ->assertSee('5:00pm - 6:00pm')
        ->assertSee('Thursday')
        ->assertSee('6:30pm - 7:30pm')
        ->assertSee('7:45pm - 8:30pm')
        ->assertSee('Try summer ballet and jazz technique.')
        ->assertSee('Avery worked hard all season.')
        ->assertDontSee('Other Dancer');
});

test('admins still see the placement table', function () {
    $admin = User::factory()->create([
        'email' => 'customdenlie@gmail.com',
    ]);

    Level::create([
        'first_name' => 'Avery',
        'last_name' => 'Example',
        'email' => 'parent@example.com',
        'ballet' => '3',
    ]);

    $this->actingAs($admin)
        ->get(route('levels.index'))
        ->assertOk()
        ->assertViewIs('levels.index')
        ->assertSee('Email')
        ->assertSee('Teacher Recommendation')
        ->assertSee('Teacher Comments')
        ->assertSee('Avery');
});
