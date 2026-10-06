<?php

namespace Tests\Feature\Auth;

use App\Mail\OneTimePasswordMail;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_screen_can_be_rendered_and_sends_a_code(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertOk();
        Mail::assertSent(OneTimePasswordMail::class, fn (OneTimePasswordMail $mail) => $mail->purpose === OneTimePassword::PURPOSE_EMAIL_VERIFICATION);
    }

    public function test_screen_does_not_send_another_code_while_one_is_active(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get(route('verification.notice'));

        $this->actingAs($user)->get(route('verification.notice'))->assertOk();

        Mail::assertSentCount(1);
    }

    public function test_verified_users_are_sent_to_their_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('verification.notice'))
            ->assertRedirect($user->dashboardRoute());
    }

    public function test_guests_cannot_reach_the_verification_screen(): void
    {
        $this->get(route('verification.notice'))->assertRedirect(route('login'));
    }

    public function test_unverified_users_are_redirected_from_protected_pages(): void
    {
        Mail::fake();
        $admin = User::factory()->unverified()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_correct_code_verifies_the_email(): void
    {
        Event::fake([Verified::class]);
        Mail::fake();
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get(route('verification.notice'));
        $code = $this->capturedCode();

        $response = $this->actingAs($user)->post(route('verification.verify'), ['code' => $code]);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect($user->dashboardRoute());
    }

    public function test_wrong_code_does_not_verify_the_email(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get(route('verification.notice'));
        $code = $this->capturedCode();
        $wrongCode = $code === '000000' ? '111111' : '000000';

        $response = $this->actingAs($user)->post(route('verification.verify'), ['code' => $wrongCode]);

        $response->assertSessionHasErrors('code');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_correct_code_still_works_after_a_wrong_attempt(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get(route('verification.notice'));
        $code = $this->capturedCode();
        $wrongCode = $code === '000000' ? '111111' : '000000';
        $this->actingAs($user)->post(route('verification.verify'), ['code' => $wrongCode]);

        $response = $this->actingAs($user)->post(route('verification.verify'), ['code' => $code]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_code_is_discarded_when_the_email_cannot_be_sent(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get(route('verification.notice'))->assertOk();

        $this->assertFalse(OneTimePassword::hasActiveCode($user->email, OneTimePassword::PURPOSE_EMAIL_VERIFICATION));
        $this->assertFalse(OneTimePassword::isInCooldown($user->email, OneTimePassword::PURPOSE_EMAIL_VERIFICATION));
    }

    public function test_code_must_be_six_digits(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post(route('verification.verify'), ['code' => 'abc']);

        $response->assertSessionHasErrors('code');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_expired_code_is_rejected(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get(route('verification.notice'));
        $code = $this->capturedCode();

        $this->travel(OneTimePassword::EXPIRY_MINUTES + 1)->minutes();

        $response = $this->actingAs($user)->post(route('verification.verify'), ['code' => $code]);

        $response->assertSessionHasErrors('code');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_code_is_locked_out_after_too_many_wrong_attempts(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get(route('verification.notice'));
        $code = $this->capturedCode();
        $wrongCode = $code === '000000' ? '111111' : '000000';

        for ($attempt = 0; $attempt < OneTimePassword::MAX_ATTEMPTS; $attempt++) {
            $this->actingAs($user)->post(route('verification.verify'), ['code' => $wrongCode]);
        }

        $this->actingAs($user)->post(route('verification.verify'), ['code' => $code])
            ->assertSessionHasErrors('code');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_resend_sends_a_new_code_after_the_cooldown(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get(route('verification.notice'));

        $this->travel(OneTimePassword::RESEND_COOLDOWN_SECONDS + 1)->seconds();

        $this->actingAs($user)->post(route('verification.send'))
            ->assertSessionHas('status', 'verification-code-sent');

        Mail::assertSentCount(2);
    }

    public function test_resend_is_blocked_during_the_cooldown(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get(route('verification.notice'));

        $this->actingAs($user)->post(route('verification.send'))
            ->assertSessionHasErrors('code');

        Mail::assertSentCount(1);
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
