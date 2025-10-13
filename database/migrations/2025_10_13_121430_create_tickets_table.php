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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20)->unique();
            $table->foreignId('passenger_id')->constrained()->onDelete('restrict');
            $table->foreignId('schedule_id')->constrained()->onDelete('restrict');
            $table->string('seat_number', 10);
            $table->decimal('price', 10, 2);
            $table->enum('status', ['reserved', 'paid', 'validated', 'cancelled'])->default('reserved');
            $table->text('qr_code')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('validated_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('ticket_number');
            $table->index(['schedule_id', 'seat_number']);
            $table->index('passenger_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
