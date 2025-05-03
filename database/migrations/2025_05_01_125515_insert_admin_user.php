<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('admin');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $user = \App\Models\User::query()->where('email', 'admin@admin.com')->first();

        $user->assignRole([]);

        $user->delete();
    }
};
