<?php

// ================================================================================
// 1. CREATE PROVINCES TABLE
// 2024_01_01_000001_create_provinces_table.php
// ================================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTable extends Migration
{
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 10)->unique();
            $table->timestamps();
            
            $table->index('name');
        });
        
        // Inserir províncias de Moçambique
        DB::table('provinces')->insert([
            ['name' => 'Maputo Cidade', 'code' => 'MPC'],
            ['name' => 'Maputo', 'code' => 'MP'],
            ['name' => 'Gaza', 'code' => 'GZ'],
            ['name' => 'Inhambane', 'code' => 'IN'],
            ['name' => 'Sofala', 'code' => 'SF'],
            ['name' => 'Manica', 'code' => 'MN'],
            ['name' => 'Tete', 'code' => 'TT'],
            ['name' => 'Zambézia', 'code' => 'ZB'],
            ['name' => 'Nampula', 'code' => 'NP'],
            ['name' => 'Cabo Delgado', 'code' => 'CD'],
            ['name' => 'Niassa', 'code' => 'NS'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('provinces');
    }
}

// ================================================================================
// 2. CREATE CITIES TABLE
// 2024_01_01_000002_create_cities_table.php
// ================================================================================

class CreateCitiesTable extends Migration
{
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->foreignId('province_id')->constrained()->onDelete('restrict');
            $table->timestamps();
            
            $table->index('name');
            $table->index('province_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
}

// ================================================================================
// 3. CREATE USERS TABLE (Funcionários)
// 2024_01_01_000003_create_users_table.php
// ================================================================================

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'operator'])->default('operator');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            
            $table->index('email');
            $table->index('role');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

// ================================================================================
// 4. CREATE PASSENGERS TABLE
// 2024_01_01_000004_create_passengers_table.php
// ================================================================================

class CreatePassengersTable extends Migration
{
    public function up()
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->unique();
            $table->string('password');
            $table->enum('document_type', ['bi', 'passport', 'dire'])->nullable();
            $table->string('document_number', 50)->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('email');
            $table->index('phone');
            $table->index(['first_name', 'last_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('passengers');
    }
}

// ================================================================================
// 5. CREATE ROUTES TABLE
// 2024_01_01_000005_create_routes_table.php
// ================================================================================

class CreateRoutesTable extends Migration
{
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_city_id')->constrained('cities')->onDelete('restrict');
            $table->foreignId('destination_city_id')->constrained('cities')->onDelete('restrict');
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->integer('estimated_duration_minutes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['origin_city_id', 'destination_city_id']);
            $table->index('origin_city_id');
            $table->index('destination_city_id');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('routes');
    }
}

// ================================================================================
// 6. CREATE BUSES TABLE
// 2024_01_01_000006_create_buses_table.php
// ================================================================================

class CreateBusesTable extends Migration
{
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number', 20)->unique();
            $table->string('model', 100);
            $table->integer('total_seats');
            $table->json('seat_configuration')->nullable(); // Layout dos assentos
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('registration_number');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('buses');
    }
}

// ================================================================================
// 7. CREATE SCHEDULES TABLE
// 2024_01_01_000007_create_schedules_table.php
// ================================================================================

class CreateSchedulesTable extends Migration
{
    public function up()
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

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}

// ================================================================================
// 8. CREATE TICKETS TABLE
// 2024_01_01_000008_create_tickets_table.php
// ================================================================================

class CreateTicketsTable extends Migration
{
    public function up()
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

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}

// ================================================================================
// 9. CREATE PAYMENTS TABLE
// 2024_01_01_000009_create_payments_table.php
// ================================================================================

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('restrict');
            $table->string('transaction_reference', 100)->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['mpesa', 'emola', 'cash', 'pos'])->default('mpesa');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index('transaction_reference');
            $table->index('status');
            $table->index('payment_method');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

// ================================================================================
// 10. CREATE TEMPORARY RESERVATIONS TABLE
// 2024_01_01_000010_create_temporary_reservations_table.php
// ================================================================================

class CreateTemporaryReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('temporary_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 100);
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->string('seat_number', 10);
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->unique(['schedule_id', 'seat_number']);
            $table->index('session_id');
            $table->index('expires_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('temporary_reservations');
    }
}

// ================================================================================
// 11. CREATE PASSWORD RESETS TABLE
// 2024_01_01_000011_create_password_resets_table.php
// ================================================================================

class CreatePasswordResetsTable extends Migration
{
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}

// ================================================================================
// 12. CREATE JOBS TABLE (Para Queue)
// 2024_01_01_000012_create_jobs_table.php
// ================================================================================

class CreateJobsTable extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}

// ================================================================================
// 13. CREATE FAILED JOBS TABLE
// 2024_01_01_000013_create_failed_jobs_table.php
// ================================================================================

class CreateFailedJobsTable extends Migration
{
    public function up()
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('failed_jobs');
    }
}

// ================================================================================
// 14. CREATE SESSIONS TABLE
// 2024_01_01_000014_create_sessions_table.php
// ================================================================================

class CreateSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}