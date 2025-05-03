<?php

use Filament\Facades\Filament;

it('can not access admin panel without authentication', function () {
    $response = $this->get('/admin');

    $response->assertStatus(302);

    $response->assertRedirect(Filament::getPanel('user')->getLoginUrl());
});

it('can not access user panel without authentication', function () {
    $response = $this->get('/user');

    $response->assertStatus(302);

    $response->assertRedirect(Filament::getPanel('user')->getLoginUrl());
});