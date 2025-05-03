<?php

namespace Tests;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AdminTestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::role('admin')->first());

        Filament::setCurrentPanel(
            Filament::getPanel('admin'),
        );
    }
}
