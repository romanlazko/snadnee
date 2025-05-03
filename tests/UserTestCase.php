<?php

namespace Tests;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class UserTestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

        Filament::setCurrentPanel(
            Filament::getPanel('user'),
        );
    }
}
