<?php

use App\Models\User;

it('can not access admin panel for user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});
