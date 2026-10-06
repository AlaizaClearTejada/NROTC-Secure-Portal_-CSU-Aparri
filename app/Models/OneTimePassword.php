<?php

namespace App\Models;

use App\Mail\OneTimePasswordMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OneTimePassword extends Model
{
    // ── Purposes ──────────────────────────────────────────────────────────────
    const PURPOSE_EMAIL_VERIFICATION = 'email_verification';

    const PURPOSE_PASSWORD_RESET = 'password_reset';

    // ── Policy ────────────────────────────────────────────────────────────────
    const CODE_LENGTH = 6;

    const EXPIRY_MINUTES = 10;

    const MAX_ATTEMPTS = 5;

    const RESEND_COOLDOWN_SECONDS = 60;

    /** @var array<int, string> */
    protected $fillable = [
        'email',
        'purpose',
        'code_hash',
        'attempts',
        'expires_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attempts' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Create a new code for the email and purpose, replacing any previous one.
     * Returns the plain-text code so it can be emailed; only its hash is stored.
     */
    public static function issue(string $email, string $purpose): string
    {
        $code = str_pad((string) random_int(0, 10 ** self::CODE_LENGTH - 1), self::CODE_LENGTH, '0', STR_PAD_LEFT);

        static::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->delete();

        static::query()->create([
            'email' => $email,
            'purpose' => $purpose,
            'code_hash' => Hash::make($code),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
        ]);

        return $code;
    }

    /**
     * Issue a code and email it. If sending fails the code is discarded, so the
     * user is not blocked by a cooldown for a code they never received.
     */
    public static function send(string $email, string $purpose, string $recipientName): void
    {
        $code = static::issue($email, $purpose);

        try {
            Mail::to($email)->send(new OneTimePasswordMail($code, $purpose, $recipientName));
        } catch (\Throwable $exception) {
            static::forget($email, $purpose);

            throw $exception;
        }
    }

    /**
     * True when a code was issued recently enough that another should not be sent yet.
     */
    public static function isInCooldown(string $email, string $purpose): bool
    {
        return static::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->where('created_at', '>', now()->subSeconds(self::RESEND_COOLDOWN_SECONDS))
            ->exists();
    }

    /**
     * True when an unexpired code is already waiting to be entered.
     */
    public static function hasActiveCode(string $email, string $purpose): bool
    {
        return static::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Check a submitted code. A correct code is consumed. Too many wrong
     * attempts, or an expired code, invalidate it entirely.
     */
    public static function verifyCode(string $email, string $purpose, string $code): bool
    {
        $record = static::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->latest('id')
            ->first();

        if (! $record || $record->expires_at->isPast() || $record->attempts >= self::MAX_ATTEMPTS) {
            $record?->delete();

            return false;
        }

        if (! Hash::check($code, $record->code_hash)) {
            $record->increment('attempts');

            return false;
        }

        static::query()->where('email', $email)->where('purpose', $purpose)->delete();

        return true;
    }

    /**
     * Remove any outstanding codes for the email and purpose.
     */
    public static function forget(string $email, string $purpose): void
    {
        static::query()->where('email', $email)->where('purpose', $purpose)->delete();
    }
}
