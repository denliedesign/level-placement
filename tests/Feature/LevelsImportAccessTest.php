<?php

use App\Models\User;

test('guests are redirected away from the levels import page', function () {
    $this->get(route('levels.import.form'))
        ->assertRedirect(route('login', absolute: false));
});

test('non admin users cannot view the levels import page', function () {
    $user = User::factory()->create([
        'email' => 'family@example.com',
    ]);

    $this->actingAs($user)
        ->get(route('levels.import.form'))
        ->assertForbidden();
});

test('admin users can view the levels import page', function () {
    $admin = User::factory()->create([
        'email' => 'customdenlie@gmail.com',
    ]);

    $this->actingAs($admin)
        ->get(route('levels.import.form'))
        ->assertOk();
});
