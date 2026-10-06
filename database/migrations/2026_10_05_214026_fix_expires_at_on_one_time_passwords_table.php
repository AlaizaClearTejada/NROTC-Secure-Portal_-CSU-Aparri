<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MySQL/MariaDB give the first non-null TIMESTAMP column an implicit
     * "ON UPDATE CURRENT_TIMESTAMP", which reset expires_at on every wrong
     * attempt and expired the OTP immediately. A nullable column has no
     * implicit default, so redefining it removes that behaviour.
     */
    public function up(): void
    {
        Schema::table('one_time_passwords', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
