<?php

use App\Models\DanceClass;
use App\Models\Level;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('families can filter dance classes in the scheduler', function () {
    DanceClass::create([
        'name' => 'Jazz 4',
        'dance_style' => 'Jazz',
        'level' => '4',
        'day_of_week' => 'Wednesday',
        'time' => '5:00pm - 6:00pm',
    ]);

    DanceClass::create([
        'name' => 'Ballet 2',
        'dance_style' => 'Ballet',
        'level' => '2',
        'day_of_week' => 'Monday',
        'time' => '4:00pm - 5:00pm',
    ]);

    $this->post(route('dance-classes.filter'), [
        'level' => ['4'],
        'dance_style' => ['Jazz'],
        'day_of_week' => ['Wednesday'],
    ])
        ->assertOk()
        ->assertViewIs('dance-classes.results')
        ->assertSee('Jazz')
        ->assertSee('4')
        ->assertSee('5:00pm - 6:00pm')
        ->assertDontSee('Ballet 2');
});

test('scheduler includes placement matches by style and level for logged in families', function () {
    $user = User::factory()->create([
        'email' => 'parent@example.com',
    ]);

    Level::create([
        'first_name' => 'Avery',
        'last_name' => 'Example',
        'email' => 'parent@example.com',
        'jazz' => '4',
    ]);

    DanceClass::create([
        'name' => 'Thursday Jazz Option',
        'dance_style' => 'Jazz',
        'level' => '4',
        'day_of_week' => 'Wednesday',
        'time' => '5:00pm - 6:00pm',
    ]);

    $this->actingAs($user)
        ->get(route('dance-classes.scheduler'))
        ->assertOk()
        ->assertSee('Placement Match')
        ->assertSee('"style":"Jazz"', false)
        ->assertSee('"level":"4"', false);
});

test('scheduler placement matches include combined numeric class levels', function () {
    $user = User::factory()->create([
        'email' => 'parent@example.com',
    ]);

    Level::create([
        'first_name' => 'Avery',
        'last_name' => 'Example',
        'email' => 'parent@example.com',
        'jazz' => '5',
        'acro' => '2',
    ]);

    DanceClass::create([
        'name' => 'Upper Jazz',
        'dance_style' => 'Jazz',
        'level' => '5/6',
        'day_of_week' => 'Wednesday',
        'time' => '5:00pm - 6:00pm',
    ]);

    DanceClass::create([
        'name' => 'Acro Foundations',
        'dance_style' => 'Acro',
        'level' => '1-2',
        'day_of_week' => 'Thursday',
        'time' => '5:00pm - 6:00pm',
    ]);

    $this->actingAs($user)
        ->get(route('dance-classes.scheduler'))
        ->assertOk()
        ->assertSee('"level":"5\/6"', false)
        ->assertSee('1-2')
        ->assertSee('Placement Match');
});

test('scheduler level filter options expand combined class levels', function () {
    DanceClass::create([
        'name' => 'Acro Foundations',
        'dance_style' => 'Acro',
        'level' => '1-2',
        'day_of_week' => 'Thursday',
        'time' => '5:00pm - 6:00pm',
    ]);

    DanceClass::create([
        'name' => 'Upper Jazz',
        'dance_style' => 'Jazz',
        'level' => '4/5',
        'day_of_week' => 'Wednesday',
        'time' => '5:00pm - 6:00pm',
    ]);

    $this->get(route('dance-classes.scheduler'))
        ->assertOk()
        ->assertSee('const schedulerLevelOptions = ["1","2","4","5"];', false)
        ->assertSee('"level":"1-2"', false)
        ->assertSee('"level":"4\/5"', false);
});

test('server side scheduler level filters include combined class levels', function () {
    DanceClass::create([
        'name' => 'Upper Jazz',
        'dance_style' => 'Jazz',
        'level' => '4/5',
        'day_of_week' => 'Wednesday',
        'time' => '5:00pm - 6:00pm',
    ]);

    DanceClass::create([
        'name' => 'Advanced Jazz',
        'dance_style' => 'Jazz',
        'level' => '6',
        'day_of_week' => 'Thursday',
        'time' => '5:00pm - 6:00pm',
    ]);

    $this->post(route('dance-classes.filter'), [
        'level' => ['5'],
        'dance_style' => ['Jazz'],
    ])
        ->assertOk()
        ->assertSee('4/5')
        ->assertDontSee('6');
});

test('placement exact class lookup includes combined numeric class levels', function () {
    $level = Level::create([
        'first_name' => 'Avery',
        'last_name' => 'Example',
        'email' => 'parent@example.com',
        'jazz' => '5',
        'acro' => '2',
    ]);

    DanceClass::create([
        'name' => 'Upper Jazz',
        'dance_style' => 'Jazz',
        'level' => '5/6',
        'day_of_week' => 'Wednesday',
        'time' => '5:00pm - 6:00pm',
    ]);

    DanceClass::create([
        'name' => 'Acro Foundations',
        'dance_style' => 'Acro',
        'level' => '1-2',
        'day_of_week' => 'Thursday',
        'time' => '5:00pm - 6:00pm',
    ]);

    expect($level->matchingDanceClasses()->pluck('level')->all())->toBe(['1-2', '5/6']);
});

test('scheduler prepopulates favorite email for logged in families', function () {
    $user = User::factory()->create([
        'email' => 'parent@example.com',
    ]);

    $this->actingAs($user)
        ->get(route('dance-classes.scheduler'))
        ->assertOk()
        ->assertSee('parent@example.com');
});

test('scheduler does not include placement match names for guests', function () {
    DanceClass::create([
        'name' => 'Jazz 4',
        'dance_style' => 'Jazz',
        'level' => '4',
        'day_of_week' => 'Wednesday',
        'time' => '5:00pm - 6:00pm',
    ]);

    $this->get(route('dance-classes.scheduler'))
        ->assertOk()
        ->assertDontSee('Highlighted rows are exact options');
});

test('only admins can view the dance classes import page', function () {
    $user = User::factory()->create([
        'email' => 'family@example.com',
    ]);

    $admin = User::factory()->create([
        'email' => 'customdenlie@gmail.com',
    ]);

    $this->get(route('dance-classes.import.form'))
        ->assertRedirect(route('login', absolute: false));

    $this->actingAs($user)
        ->get(route('dance-classes.import.form'))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('dance-classes.import.form'))
        ->assertOk();
});

test('families can download selected favorites as csv', function () {
    $danceClass = DanceClass::create([
        'name' => 'Jazz 4',
        'dance_style' => 'Jazz',
        'level' => '4',
        'day_of_week' => 'Wednesday',
        'time' => '5:00pm - 6:00pm',
    ]);

    $this->post(route('dance-classes.favorites.download'), [
        'selected_classes' => [$danceClass->id],
    ])
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');
});

test('families can email selected favorites', function () {
    Mail::fake();

    $danceClass = DanceClass::create([
        'name' => 'Jazz 4',
        'dance_style' => 'Jazz',
        'level' => '4',
        'day_of_week' => 'Wednesday',
        'time' => '5:00pm - 6:00pm',
    ]);

    $this->from(route('dance-classes.scheduler'))
        ->post(route('dance-classes.favorites.email'), [
            'email' => 'parent@example.com',
            'selected_classes' => [$danceClass->id],
        ])
        ->assertRedirect(route('dance-classes.scheduler'));

    Mail::assertSent(\App\Mail\FavoritesEmail::class);
});

test('families can email selected favorites without a full page refresh', function () {
    Mail::fake();

    $danceClass = DanceClass::create([
        'name' => 'Jazz 4',
        'dance_style' => 'Jazz',
        'level' => '4',
        'day_of_week' => 'Wednesday',
        'time' => '5:00pm - 6:00pm',
    ]);

    $this->postJson(route('dance-classes.favorites.email'), [
        'email' => 'parent@example.com',
        'selected_classes' => [$danceClass->id],
    ])
        ->assertOk()
        ->assertJson([
            'message' => 'Favorites sent to parent@example.com.',
        ]);

    Mail::assertSent(\App\Mail\FavoritesEmail::class);
});
