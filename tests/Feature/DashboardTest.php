<?php

use App\Models\User;

test('admins see import controls on the dashboard', function () {
    $admin = User::factory()->create([
        'email' => 'customdenlie@gmail.com',
    ]);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Admin Dashboard')
        ->assertSee('Import Levels')
        ->assertSee('Import Dance Classes')
        ->assertSee(route('levels.import.form', absolute: false))
        ->assertSee(route('dance-classes.import.form', absolute: false));
});

test('families do not see import controls on the dashboard', function () {
    $user = User::factory()->create([
        'email' => 'family@example.com',
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('View Level Placement')
        ->assertDontSee('Admin Dashboard')
        ->assertDontSee('Import Levels')
        ->assertDontSee('Import Dance Classes');
});
