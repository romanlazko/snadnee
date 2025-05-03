<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roles = [
            'admin',
            'user',
        ];

        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create(['name' => $role]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Spatie\Permission\Models\Role::query()->whereIn('name', ['admin', 'user'])->delete();
    }
};
