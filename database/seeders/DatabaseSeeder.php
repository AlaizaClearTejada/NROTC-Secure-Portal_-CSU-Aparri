<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the NROTC system with default accounts for each role.
     *
     * IMPORTANT: Change these credentials immediately after first deployment.
     * Passwords satisfy NIST SP 800-63B complexity requirements:
     *   - Minimum 12 characters
     *   - Mix of uppercase, lowercase, digit, and special character
     */
    public function run(): void
    {
        // ── Simple Test Accounts ──────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'simpleadmin@nrotc.csu.edu.ph'],
            [
                'name' => 'Simple Admin',
                'student_id' => 'admin',
                'password' => Hash::make('Password123!'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => Carbon::now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'simplecadet@nrotc.csu.edu.ph'],
            [
                'name' => 'Simple Cadet',
                'student_id' => 'cadet',
                'password' => Hash::make('Password123!'),
                'role' => User::ROLE_CADET,
                'is_active' => true,
                'enrollment_status' => User::ENROLLMENT_APPROVED,
                'email_verified_at' => Carbon::now(),
            ]
        );

        // ── Administrator ─────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@nrotc.csu.edu.ph'],
            [
                'name' => 'System Administrator',
                'student_id' => 'ADMIN-001',
                'password' => Hash::make('Admin@NROTC2026!'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => Carbon::now(),
            ]
        );

        // ── Officer ───────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'officer@nrotc.csu.edu.ph'],
            [
                'name' => 'Tactical Officer',
                'student_id' => 'OFC-001',
                'password' => Hash::make('Officer@NROTC2026!'),
                'role' => User::ROLE_OFFICER,
                'is_active' => true,
                'email_verified_at' => Carbon::now(),
            ]
        );

        // ── Seed Cadets in Various States ───────────────────────────────────────
        
        // 1. Approved Cadet (Active)
        User::factory()->create([
            'name' => 'Approved Cadet',
            'email' => 'approved@nrotc.csu.edu.ph',
            'student_id' => '2024-00001',
            'password' => Hash::make('Cadet@NROTC2026!'),
            'role' => User::ROLE_CADET,
            'is_active' => true,
            'enrollment_status' => User::ENROLLMENT_APPROVED,
        ]);

        // 2. Pending Review Cadet
        User::factory()->create([
            'name' => 'Pending Cadet',
            'email' => 'pending@nrotc.csu.edu.ph',
            'student_id' => '2024-00002',
            'password' => Hash::make('Cadet@NROTC2026!'),
            'role' => User::ROLE_CADET,
            'is_active' => false,
            'enrollment_status' => User::ENROLLMENT_PENDING_REVIEW,
        ]);

        // 3. Under Medical Review Cadet
        User::factory()->create([
            'name' => 'Medical Review Cadet',
            'email' => 'medical@nrotc.csu.edu.ph',
            'student_id' => '2024-00003',
            'password' => Hash::make('Cadet@NROTC2026!'),
            'role' => User::ROLE_CADET,
            'is_active' => false,
            'enrollment_status' => User::ENROLLMENT_MEDICAL_REVIEW,
        ]);

        // 4. Revision Requested Cadet
        User::factory()->create([
            'name' => 'Revision Cadet',
            'email' => 'revision@nrotc.csu.edu.ph',
            'student_id' => '2024-00004',
            'password' => Hash::make('Cadet@NROTC2026!'),
            'role' => User::ROLE_CADET,
            'is_active' => false,
            'enrollment_status' => User::ENROLLMENT_REVISION_REQUESTED,
            'revision_notes' => 'Please upload a clearer copy of your medical certificate. The current one is too blurry to read.',
        ]);

        // 5. Rejected Cadet
        User::factory()->create([
            'name' => 'Rejected Cadet',
            'email' => 'rejected@nrotc.csu.edu.ph',
            'student_id' => '2024-00005',
            'password' => Hash::make('Cadet@NROTC2026!'),
            'role' => User::ROLE_CADET,
            'is_active' => false,
            'enrollment_status' => User::ENROLLMENT_REJECTED,
            'enrollment_remarks' => 'Does not meet the height requirement for the program.',
        ]);

        // Generate 10 random pending cadets
        User::factory()->count(10)->create([
            'role' => User::ROLE_CADET,
            'is_active' => false,
            'enrollment_status' => User::ENROLLMENT_PENDING_REVIEW,
        ]);
    }
}
