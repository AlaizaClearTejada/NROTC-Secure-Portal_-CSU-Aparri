<?php

namespace Tests\Feature\Auth;

use App\Mail\OneTimePasswordMail;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $this->get(route('password.request'))->assertOk();
    }

    public function test_reset_code_is_emailed_for_an_existing_account(): void
    {
        Mail::fake();
        $user = User::factory()->create(['email' => 'cadet@example.com']);

        $response = $this->post(route('password.email'), ['email' => 'cadet@example.com']);

        $response->assertRedirect(route('password.otp'));
        $response->assertSessionHas('password_reset_email', 'cadet@example.com');
        Mail::assertSent(OneTimePasswordMail::class, fn (OneTimePasswordMail $mail) => $mail->purpose === OneTimePassword::PURPOSE_PASSWORD_RESET);
    }

    public function test_unregistered_email_is_told_it_has_no_account_and_nothing_is_sent(): void
    {
        Mail::fake();

        $response = $this->post(route('password.email'), ['email' => 'nobody@example.com']);

        $response->assertSessionHasErrors(['email' => 'This email is not registered in the system.']);
        $response->assertSessionMissing('password_reset_email');
        Mail::assertNothingSent();
    }

    public function test_email_is_required_and_must_be_valid(): void
    {
        $this->post(route('password.email'), ['email' => ''])->assertSessionHasErrors('email');
        $this->post(route('password.email'), ['email' => 'not-an-email'])->assertSessionHasErrors('email');
    }

    public function test_code_screen_redirects_back_when_no_reset_is_in_progress(): void
    {
        $this->get(route('password.otp'))->assertRedirect(route('password.request'));
    }

    public function test_correct_code_opens_the_new_password_step(): void
    {
        Mail::fake();
        $user = User::factory()->create(['email' => 'cadet@example.com']);
        $this->post(route('password.email'), ['email' => 'cadet@example.com']);
        $code = $this->capturedCode();

        $response = $this->post(route('password.otp.verify'), ['code' => $code]);

        $response->assertRedirect(route('password.reset'));
        $this->get(route('password.reset'))->assertOk();
    }

    public function test_wrong_code_does_not_open_the_new_password_step(): void
    {
        Mail::fake();
        User::factory()->create(['email' => 'cadet@example.com']);
        $this->post(route('password.email'), ['email' => 'cadet@example.com']);
        $code = $this->capturedCode();
        $wrongCode = $code === '000000' ? '111111' : '000000';

        $this->post(route('password.otp.verify'), ['code' => $wrongCode])
            ->assertSessionHasErrors('code');

        $this->get(route('password.reset'))->assertRedirect(route('password.request'));
    }

    public function test_new_password_step_is_closed_without_a_verified_code(): void
    {
        $this->get(route('password.reset'))->assertRedirect(route('password.request'));
    }

    public function test_password_can_be_reset_with_a_verified_code(): void
    {
        Mail::fake();
        $user = User::factory()->create(['email' => 'cadet@example.com', 'login_attempts' => 3]);
        $this->post(route('password.email'), ['email' => 'cadet@example.com']);
        $this->post(route('password.otp.verify'), ['code' => $this->capturedCode()]);

        $response = $this->post(route('password.store'), [
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $response->assertRedirect(route('login'));
        $user->refresh();
        $this->assertTrue(Hash::check('NewPassword123', $user->password));
        $this->assertSame(0, $user->login_attempts);
        $this->assertDatabaseMissing('one_time_passwords', ['email' => 'cadet@example.com']);
        $this->get(route('password.reset'))->assertRedirect(route('password.request'));
    }

    public function test_code_cannot_be_reused_after_a_successful_reset(): void
    {
        Mail::fake();
        User::factory()->create(['email' => 'cadet@example.com']);
        $this->post(route('password.email'), ['email' => 'cadet@example.com']);
        $code = $this->capturedCode();
        $this->post(route('password.otp.verify'), ['code' => $code]);
        $this->post(route('password.store'), [
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $this->post(route('password.email'), ['email' => 'cadet@example.com']);
        $this->post(route('password.otp.verify'), ['code' => $code])
            ->assertSessionHasErrors('code');
    }

    public function test_new_password_must_match_confirmation_and_meet_policy(): void
    {
        Mail::fake();
        User::factory()->create(['email' => 'cadet@example.com']);
        $this->post(route('password.email'), ['email' => 'cadet@example.com']);
        $this->post(route('password.otp.verify'), ['code' => $this->capturedCode()]);

        $this->post(route('password.store'), [
            'password' => 'NewPassword123',
            'password_confirmation' => 'Different123',
        ])->assertSessionHasErrors('password');

        $this->post(route('password.store'), [
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ])->assertSessionHasErrors('password');
    }

    public function test_resend_sends_a_new_code_after_the_cooldown(): void
    {
        Mail::fake();
        User::factory()->create(['email' => 'cadet@example.com']);
        $this->post(route('password.email'), ['email' => 'cadet@example.com']);

        $this->travel(OneTimePassword::RESEND_COOLDOWN_SECONDS + 1)->seconds();

        $this->post(route('password.otp.resend'))
            ->assertSessionHas('status', 'A new OTP has been sent.');

        Mail::assertSentCount(2);
    }

    public function test_resend_is_blocked_during_the_cooldown(): void
    {
        Mail::fake();
        User::factory()->create(['email' => 'cadet@example.com']);
        $this->post(route('password.email'), ['email' => 'cadet@example.com']);

        $this->post(route('password.otp.resend'))->assertSessionHasErrors('code');

        Mail::assertSentCount(1);
    }

    /**
     * Read the code from the most recently sent reset email.
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
