<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wifi_vouchers', function (Blueprint $table) {
            $table->string('import_batch')->nullable()->after('voucher_code');
            $table->timestamp('imported_at')->nullable()->after('import_batch');
            $table->index(['status', 'student_id', 'visitor_id']);
        });
    }

    public function down(): void
    {
        Schema::table('wifi_vouchers', function (Blueprint $table) {
            $table->dropIndex(['status', 'student_id', 'visitor_id']);
            $table->dropColumn(['import_batch', 'imported_at']);
        });
    }
};
