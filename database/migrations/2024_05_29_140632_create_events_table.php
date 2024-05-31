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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('title', 255);
            $table->text('content');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->unsignedInteger('max_people')->default(0);
            $table->unsignedDecimal('price', $precision = 10, $scale = 2)->default(0);
            $table->string('image', 255)->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
