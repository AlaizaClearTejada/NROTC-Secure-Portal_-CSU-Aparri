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
            $table->string('diploma_path')->nullable()->after('emergency_contact');
            $table->string('birth_certificate_path')->nullable()->after('diploma_path');
            $table->string('medical_path')->nullable()->after('birth_certificate_path');
            $table->string('parent_id_path')->nullable()->after('medical_path');
            $table->text('revision_notes')->nullable()->after('enrollment_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'diploma_path',
                'birth_certificate_path',
                'medical_path',
                'parent_id_path',
                'revision_notes',
            ]);
        });
    }
};
