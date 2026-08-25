<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wifi_vouchers', function (Blueprint $table) {
            $table->string('network_name')->nullable()->after('voucher_type');
        });
    }

    public function down(): void
    {
        Schema::table('wifi_vouchers', function (Blueprint $table) {
            $table->dropColumn('network_name');
        });
    }
};
