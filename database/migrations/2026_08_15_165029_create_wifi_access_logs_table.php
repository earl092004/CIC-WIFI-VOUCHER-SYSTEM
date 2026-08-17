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
       Schema::create('wifi_access_logs', function (Blueprint $table) {
    $table->id();

    $table->foreignId('student_id')
        ->nullable()
        ->constrained('students')
        ->nullOnDelete();

    $table->foreignId('visitor_id')
        ->nullable()
        ->constrained('visitors')
        ->nullOnDelete();

    $table->foreignId('voucher_id')
        ->nullable()
        ->constrained('wifi_vouchers')
        ->nullOnDelete();

    $table->foreignId('performed_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->string('action');

    $table->ipAddress('ip_address')->nullable();
    $table->string('device_mac')->nullable();

    $table->text('description')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wifi_access_logs');
    }
};
