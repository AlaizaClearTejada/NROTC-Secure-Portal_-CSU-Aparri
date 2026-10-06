<?php

namespace Tests\Feature\Auth;

use App\Mail\OneTimePasswordMail;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, mixed> */
    private function detailsPayload(array $overrides = []): array
    {
        return array_merge([
            'last_name' => 'Dela Cruz',
            'first_name' => 'Juan',
            'middle_name' => null,
            'suffix' => null,
            'student_id' => '2026-00123',
            'email' => 'juan@example.com',
        ], $overrides);
    }

    /**
     * Submit step 1 and return the code that was emailed.
     */
    private function startRegistration(array $overrides = []): string
    {
        $this->post(route('register'), $this->detailsPayload($overrides));

        return $this->capturedCode();
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $this->get(route('register'))->assertOk();
    }

    public function test_details_step_emails_a_code_and_does_not_create_an_account(): void
    {
        Mail::fake();

        $response = $this->post(route('register'), $this->detailsPayload());

        $response->assertRedirect(route('register.verify'));
        $this->assertDatabaseMissing('users', ['email' => 'juan@example.com']);
        Mail::assertSent(OneTimePasswordMail::class, fn (OneTimePasswordMail $mail) => $mail->purpose === OneTimePassword::PURPOSE_EMAIL_VERIFICATION);
    }

    public function test_details_step_requires_a_unique_email_and_student_id(): void
    {
        User::factory()->create(['email' => 'juan@example.com', 'student_id' => '2026-00999']);

        $this->post(route('register'), $this->detailsPayload())->assertSessionHasErrors('email');
        $this->post(route('register'), $this->detailsPayload(['email' => 'other@example.com', 'student_id' => '2026-00999']))
            ->assertSessionHasErrors('student_id');
    }

    public function test_code_screen_redirects_to_details_without_a_pending_registration(): void
    {
        $this->get(route('register.verify'))->assertRedirect(route('register'));
    }

    public function test_password_step_is_locked_until_the_code_is_verified(): void
    {
        Mail::fake();
        $this->post(route('register'), $this->detailsPayload());

        $this->get(route('register.password'))->assertRedirect(route('register'));
        $this->post(route('register.password.store'), [
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ])->assertRedirect(route('register'));

        $this->assertDatabaseMissing('users', ['email' => 'juan@example.com']);
    }

    public function test_wrong_code_does_not_unlock_the_password_step(): void
    {
        Mail::fake();
        $code = $this->startRegistration();
        $wrongCode = $code === '000000' ? '111111' : '000000';

        $this->post(route('register.verify.store'), ['code' => $wrongCode])
            ->assertSessionHasErrors('code');
        $this->get(route('register.password'))->assertRedirect(route('register'));
    }

    public function test_correct_code_unlocks_the_password_step(): void
    {
        Mail::fake();
        $code = $this->startRegistration();

        $this->post(route('register.verify.store'), ['code' => $code])
            ->assertRedirect(route('register.password'));
        $this->get(route('register.password'))->assertOk();
    }

    public function test_account_is_created_with_a_verified_email_and_chosen_password(): void
    {
        Mail::fake();
        $code = $this->startRegistration();
        $this->post(route('register.verify.store'), ['code' => $code]);

        $response = $this->post(route('register.password.store'), [
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $response->assertRedirect(route('login', ['from' => 'enroll']));
        $user = User::where('email', 'juan@example.com')->firstOrFail();
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(Hash::check('NewPassword123', $user->password));
        $this->assertSame(User::ROLE_CADET, $user->role);
        $this->assertFalse($user->is_active);
        $this->assertGuest();
        $this->get(route('register.password'))->assertRedirect(route('register'));
    }

    public function test_password_must_match_and_meet_policy(): void
    {
        Mail::fake();
        $code = $this->startRegistration();
        $this->post(route('register.verify.store'), ['code' => $code]);

        $this->post(route('register.password.store'), [
            'password' => 'NewPassword123',
            'password_confirmation' => 'Different123',
        ])->assertSessionHasErrors('password');

        $this->post(route('register.password.store'), [
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'juan@example.com']);
    }

    public function test_resend_is_blocked_during_the_cooldown(): void
    {
        Mail::fake();
        $this->startRegistration();

        $this->post(route('register.verify.resend'))->assertSessionHasErrors('code');

        Mail::assertSentCount(1);
    }

    public function test_resend_sends_a_new_code_after_the_cooldown(): void
    {
        Mail::fake();
        $this->startRegistration();

        $this->travel(OneTimePassword::RESEND_COOLDOWN_SECONDS + 1)->seconds();

        $this->post(route('register.verify.resend'))
            ->assertSessionHas('status', 'A new OTP has been sent.');

        Mail::assertSentCount(2);
    }

    /**
     * Read the code from the most recently sent verification email.
     */
    private function capturedCode(): string
    {
        $code = null;

        Mail::assertSent(OneTimePasswordMail::class, function (OneTimePasswordMail $mail) use (&$code) {
            $code = $mail->code;

            return true;
        });

        return $code;
    }
}
