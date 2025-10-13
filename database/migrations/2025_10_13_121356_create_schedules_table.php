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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onDelete('restrict');
            $table->foreignId('bus_id')->constrained()->onDelete('restrict');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['active', 'full', 'departed', 'cancelled'])->default('active');
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['route_id', 'departure_date']);
            $table->index('departure_date');
            $table->index('status');
            $table->index(['departure_date', 'departure_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
