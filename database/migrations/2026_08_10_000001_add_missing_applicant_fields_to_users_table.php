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
        Schema::table('users', function (Blueprint $table) {
            $table->string('parent_consent_path')->nullable()->after('parent_id_path');
            $table->string('college', 150)->nullable()->after('course_year');
            $table->string('department', 150)->nullable()->after('college');
            $table->string('place_of_birth')->nullable()->after('date_of_birth');
            $table->text('existing_medical_conditions')->nullable()->after('blood_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'parent_consent_path',
                'college',
                'department',
                'place_of_birth',
                'existing_medical_conditions',
            ]);
        });
    }
};
