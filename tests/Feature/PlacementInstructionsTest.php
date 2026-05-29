<?php

test('home page explains how families should access level placements', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Returning Families')
        ->assertSee('New Families')
        ->assertSee('If registration says the email has already been taken')
        ->assertSee('If you have multiple dancers');
});

test('login page includes placement access instructions', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Returning Families')
        ->assertSee('Forgot your password?');
});

test('register page includes placement access instructions', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertSee('New Families')
        ->assertSee('main email from your studio account');
});
