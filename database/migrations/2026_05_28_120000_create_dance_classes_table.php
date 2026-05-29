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
        Schema::create('dance_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('age_requirement')->nullable();
            $table->string('dance_style')->nullable();
            $table->string('day_of_week');
            $table->string('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dance_classes');
    }
};
