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
        Schema::create('etickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ticket_sale_id');
            $table->unsignedInteger('user_id');
            $table->string('ticket_number', 255);
            $table->text('qr_code')->nullable();
            $table->dateTime('issued_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etickets');
    }
};
