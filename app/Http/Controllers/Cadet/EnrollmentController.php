<?php

namespace App\Http\Controllers\Cadet;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EnrollmentController extends Controller
{
    /**
     * Show the enrollment form.
     */
    public function showForm()
    {
        $user = Auth::user();
        return view('enroll-form', compact('user'));
    }

    /**
     * Handle the enrollment form submission.
     */
    public function submitForm(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user) {
            $request->validate([
                'photo'                  => $user->photo_path ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120' : 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
                'diploma_path'           => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:5120',
                'birth_certificate_path' => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:5120',
                'medical_path'           => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:5120',
                'parent_id_path'         => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:5120',
                'parent_consent_path'    => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:5120',
                'date_of_birth'          => 'required|date',
                'place_of_birth'         => 'required|string',
                'gender'                 => 'required|in:Male,Female',
                'course_year'            => 'required|string',
                'college'                => 'required|string',
                'department'             => 'required|string',
            ]);

            $address = collect([
                $request->street,
                $request->barangay,
                $request->town_city,
                $request->province,
            ])->filter()->implode(', ');

            // Handle file uploads
            $photoPath = $request->hasFile('photo') && $request->file('photo')->isValid() ? $request->file('photo')->store('photos', 'public') : $user->photo_path;
            $diplomaPath = $request->hasFile('diploma_path') && $request->file('diploma_path')->isValid() ? $request->file('diploma_path')->store('documents', 'public') : $user->diploma_path;
            $birthCertificatePath = $request->hasFile('birth_certificate_path') && $request->file('birth_certificate_path')->isValid() ? $request->file('birth_certificate_path')->store('documents', 'public') : $user->birth_certificate_path;
            $medicalPath = $request->hasFile('medical_path') && $request->file('medical_path')->isValid() ? $request->file('medical_path')->store('documents', 'public') : $user->medical_path;
            $parentIdPath = $request->hasFile('parent_id_path') && $request->file('parent_id_path')->isValid() ? $request->file('parent_id_path')->store('documents', 'public') : $user->parent_id_path;
            $parentConsentPath = $request->hasFile('parent_consent_path') && $request->file('parent_consent_path')->isValid() ? $request->file('parent_consent_path')->store('documents', 'public') : $user->parent_consent_path;

            $user->update([
                'date_of_birth'          => $request->date_of_birth,
                'gender'                 => $request->gender,
                'blood_type'             => $request->blood_type,
                'religion'               => $request->religion,
                'contact_number'         => $request->cp_nr ?? $request->contact_number,
                'course_year'            => $request->course_year,
                'college'                => $request->college,
                'department'             => $request->department,
                'place_of_birth'         => $request->place_of_birth,
                'existing_medical_conditions' => $request->existing_medical_conditions,
                'address'                => $address ?: $request->address,
                'height'                 => $request->height,
                'weight'                 => $request->weight,
                'emergency_name'         => $request->emergency_name,
                'emergency_relationship' => $request->emergency_relationship,
                'emergency_contact'      => $request->emergency_contact,
                'enrollment_status'      => User::ENROLLMENT_PENDING_REVIEW,
                'photo_path'             => $photoPath,
                'diploma_path'           => $diplomaPath,
                'birth_certificate_path' => $birthCertificatePath,
                'medical_path'           => $medicalPath,
                'parent_id_path'         => $parentIdPath,
                'parent_consent_path'    => $parentConsentPath,
            ]);
        }

        return redirect()->route('cadet.applicant.dashboard')->with('success', 'Your application has been submitted and is pending review.');
    }

    /**
     * Show the applicant dashboard.
     */
    public function applicantDashboard()
    {
        $user = Auth::user();
        return view('cadet.applicant-dashboard', compact('user'));
    }
}
