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
        Schema::table('tickets', function (Blueprint $table) {
               // Campos de cancelamento (se não existirem)
            if (!Schema::hasColumn('tickets', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('validated_at');
            }
            
            if (!Schema::hasColumn('tickets', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('cancelled_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
             $table->dropColumn(['cancelled_at', 'cancellation_reason']);
        });
    }
};
