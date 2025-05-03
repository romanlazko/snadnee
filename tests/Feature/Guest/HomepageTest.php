<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('homepage returns 200', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('homepage has login and register buttons', function () {
    $response = $this->get('/');

    $response->assertSee('Log in');
    $response->assertSee('Register');
});

test('homepage has dashboard button', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertSee('Dashboard');
});