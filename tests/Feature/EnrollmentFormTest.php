<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EnrollmentFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_applicant_can_open_the_form(): void
    {
        $applicant = User::factory()->create(['is_active' => false, 'enrollment_status' => null]);

        $this->actingAs($applicant)->get(route('enroll.form'))->assertOk();
    }

    public function test_applicant_asked_for_revision_can_resubmit(): void
    {
        Storage::fake('public');
        $applicant = User::factory()->create(['is_active' => false, 'enrollment_status' => User::ENROLLMENT_REVISION_REQUESTED]);

        $this->actingAs($applicant)->get(route('enroll.form'))->assertOk();
        $this->actingAs($applicant)->post(route('enroll.form.submit'), $this->formData())
            ->assertRedirect(route('cadet.applicant.dashboard'));

        $this->assertSame(User::ENROLLMENT_PENDING_REVIEW, $applicant->fresh()->enrollment_status);
    }

    public function test_approved_cadet_cannot_open_the_form(): void
    {
        $cadet = User::factory()->create(['enrollment_status' => User::ENROLLMENT_APPROVED]);

        $this->actingAs($cadet)->get(route('enroll.form'))
            ->assertRedirect(route('cadet.dashboard'))
            ->assertSessionHas('enrollment_notice');
    }

    public function test_approved_cadet_cannot_resubmit_the_form(): void
    {
        Storage::fake('public');
        $cadet = User::factory()->create(['enrollment_status' => User::ENROLLMENT_APPROVED, 'course_year' => 'BSIT 2']);

        $this->actingAs($cadet)->post(route('enroll.form.submit'), $this->formData())
            ->assertRedirect(route('cadet.dashboard'));

        $cadet->refresh();
        $this->assertSame(User::ENROLLMENT_APPROVED, $cadet->enrollment_status);
        $this->assertSame('BSIT 2', $cadet->course_year);
        Storage::disk('public')->assertDirectoryEmpty('photos');
    }

    public function test_officers_cannot_submit_the_form(): void
    {
        $officer = User::factory()->create(['role' => User::ROLE_OFFICER]);

        $this->actingAs($officer)->post(route('enroll.form.submit'), $this->formData())
            ->assertRedirect(route('officer.dashboard'));

        $this->assertNull($officer->fresh()->enrollment_status);
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        return [
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'date_of_birth' => '2005-01-01',
            'place_of_birth' => 'Aparri',
            'gender' => 'Male',
            'course_year' => 'BSIT 1',
            'college' => 'CICS',
            'department' => 'IT',
        ];
    }
}
