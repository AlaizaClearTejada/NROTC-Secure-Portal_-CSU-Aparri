<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // ── Role constants ────────────────────────────────────────────────────────
    const ROLE_ADMIN   = 'admin';
    const ROLE_OFFICER = 'officer';
    const ROLE_CADET   = 'cadet';

    // ── Enrollment status constants ───────────────────────────────────────────
    const ENROLLMENT_PENDING_REVIEW     = 'pending_review';
    const ENROLLMENT_MEDICAL_REVIEW     = 'under_medical_review';
    const ENROLLMENT_REVISION_REQUESTED = 'revision_requested';
    const ENROLLMENT_APPROVED           = 'approved';
    const ENROLLMENT_REJECTED           = 'rejected';

    // ── Lockout policy ────────────────────────────────────────────────────────
    const MAX_LOGIN_ATTEMPTS = 5;
    const LOCKOUT_MINUTES    = 15;

    // ── Mass-assignable fields ────────────────────────────────────────────────
    protected $fillable = [
        'name',
        'student_id',
        'email',
        'password',
        'role',
        'is_active',
        'login_attempts',
        'locked_until',
        'last_login_at',
        'photo_path',
        // Cadet profile fields
        'date_of_birth',
        'place_of_birth',
        'gender',
        'blood_type',
        'existing_medical_conditions',
        'religion',
        'contact_number',
        'course_year',
        'college',
        'department',
        'address',
        'height',
        'weight',
        'emergency_name',
        'emergency_relationship',
        'emergency_contact',
        // Enrollment
        'enrollment_status',
        'enrollment_remarks',
        'revision_notes',
        // Documents
        'diploma_path',
        'birth_certificate_path',
        'medical_path',
        'parent_id_path',
        'parent_consent_path',
    ];

    // ── Hidden from serialization ─────────────────────────────────────────────
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ── Attribute casting ─────────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'locked_until'      => 'datetime',
            'last_login_at'     => 'datetime',
            'date_of_birth'     => 'date',
        ];
    }

    /**
     * Parse name components from the single name attribute.
     */
    protected function parseNameParts(): array
    {
        $name = trim($this->attributes['name'] ?? '');
        if (! $name) {
            return ['last_name' => '', 'first_name' => '', 'middle_name' => '', 'suffix' => ''];
        }

        $knownSuffixes = ['Jr.', 'Sr.', 'II', 'III', 'IV', 'V', 'Jr', 'Sr'];
        $suffix = '';
        $lastName = '';
        $firstName = '';
        $middleName = '';

        if (str_contains($name, ',')) {
            [$lastPart, $restPart] = explode(',', $name, 2);
            $lastName = trim($lastPart);
            $words = array_values(array_filter(explode(' ', trim($restPart))));

            if (! empty($words) && in_array(end($words), $knownSuffixes, true)) {
                $suffix = array_pop($words);
            }

            if (! empty($words)) {
                $firstName = array_shift($words);
            }

            if (! empty($words)) {
                $middleName = implode(' ', $words);
            }
        } else {
            $words = array_values(array_filter(explode(' ', $name)));
            if (! empty($words) && in_array(end($words), $knownSuffixes, true)) {
                $suffix = array_pop($words);
            }
            if (count($words) === 1) {
                $firstName = $words[0];
            } elseif (count($words) === 2) {
                $firstName = $words[0];
                $lastName = $words[1];
            } elseif (count($words) > 2) {
                $firstName = array_shift($words);
                $lastName = array_pop($words);
                $middleName = implode(' ', $words);
            }
        }

        return [
            'last_name'   => $lastName,
            'first_name'  => $firstName,
            'middle_name' => $middleName,
            'suffix'      => $suffix,
        ];
    }

    public function getLastNameAttribute(): string
    {
        return $this->parseNameParts()['last_name'];
    }

    public function getFirstNameAttribute(): string
    {
        return $this->parseNameParts()['first_name'];
    }

    public function getMiddleNameAttribute(): string
    {
        return $this->parseNameParts()['middle_name'];
    }

    public function getSuffixAttribute(): string
    {
        return $this->parseNameParts()['suffix'];
    }

    // ── Role helpers ──────────────────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isOfficer(): bool
    {
        return $this->role === self::ROLE_OFFICER;
    }

    public function isCadet(): bool
    {
        return $this->role === self::ROLE_CADET;
    }

    /**
     * Returns true when the account lockout period has not yet expired.
     */
    public function isLocked(): bool
    {
        return $this->locked_until !== null && $this->locked_until->isFuture();
    }

    // ── Enrollment helpers ────────────────────────────────────────────────────
    public function isPendingEnrollment(): bool
    {
        return $this->enrollment_status === self::ENROLLMENT_PENDING_REVIEW;
    }

    public function isUnderMedicalReview(): bool
    {
        return $this->enrollment_status === self::ENROLLMENT_MEDICAL_REVIEW;
    }

    public function isRevisionRequested(): bool
    {
        return $this->enrollment_status === self::ENROLLMENT_REVISION_REQUESTED;
    }

    public function isEnrollmentApproved(): bool
    {
        return $this->enrollment_status === self::ENROLLMENT_APPROVED;
    }

    public function isEnrollmentRejected(): bool
    {
        return $this->enrollment_status === self::ENROLLMENT_REJECTED;
    }

    /**
     * Returns the home dashboard URL for this user's role.
     */
    public function dashboardRoute(): string
    {
        if ($this->isCadet() && !$this->is_active &&
            in_array($this->enrollment_status, [null, self::ENROLLMENT_PENDING_REVIEW, self::ENROLLMENT_MEDICAL_REVIEW, self::ENROLLMENT_REVISION_REQUESTED, self::ENROLLMENT_REJECTED], true)) {
            // They have not been approved yet. Route them to applicant dashboard or enroll form
            return $this->enrollment_status === null ? route('enroll.form') : route('cadet.applicant.dashboard');
        }

        return match ($this->role) {
            self::ROLE_ADMIN   => route('admin.dashboard'),
            self::ROLE_OFFICER => route('officer.dashboard'),
            self::ROLE_CADET   => route('cadet.dashboard'),
            default            => route('login'),
        };
    }

    // ── Relationships ─────────────────────────────────────────────────────────
    public function examAttempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
