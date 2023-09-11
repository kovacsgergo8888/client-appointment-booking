<?php

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
        Schema::create('opening_hours', function (Blueprint $table) {
            $table->id();
            $table->date('from');
            $table->date('to');
            $table->time('from_time');
            $table->time('to_time');
            $table->enum('repeat', ['NO_REPEAT', 'WEEKLY', 'EVEN_WEEKS', 'ODD_WEEKS']);
            $table->tinyInteger('day_of_week');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_hours');
    }
};
