<?php

use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->foreignIdFor(Table::class)->nullable()->constrained();

            $table->string('phone');
            $table->date('date');
            $table->string('time');
            $table->string('number_of_people');
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->unique(['table_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
