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
        Schema::create('wifi_vouchers', function (Blueprint $table) {
            $table->id();

            // The student or visitor who received the voucher
            $table->foreignId('student_id')
                ->nullable()
                ->constrained('students')
                ->nullOnDelete();

            $table->foreignId('visitor_id')
                ->nullable()
                ->constrained('visitors')
                ->nullOnDelete();

            // Information returned by Omada
            $table->string('omada_voucher_id')->nullable();
            $table->string('voucher_code')->unique();

            // Who issued the voucher
            $table->foreignId('issued_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('voucher_type', ['student', 'visitor']);

            $table->unsignedInteger('duration_minutes');

            $table->enum('status', [
                'active',
                'expired',
                'revoked',
            ])->default('active');

            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wifi_vouchers');
    }
};
