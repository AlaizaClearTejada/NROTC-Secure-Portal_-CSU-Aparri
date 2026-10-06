<?php

use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\PhotoController as AdminPhotoController;
use App\Http\Controllers\Cadet\AnnouncementController as CadetAnnouncementController;
use App\Http\Controllers\Cadet\AttendanceController as CadetAttendanceController;
use App\Http\Controllers\Cadet\DashboardController as CadetDashboardController;
use App\Http\Controllers\Cadet\EnrollmentController as CadetEnrollmentController;
use App\Http\Controllers\Cadet\ExamController as CadetExamController;
use App\Http\Controllers\Cadet\LectureMaterialController as CadetLectureMaterialController;
use App\Http\Controllers\Cadet\PhotoController as CadetPhotoController;
use App\Http\Controllers\Officer\AttendanceController as OfficerAttendanceController;
use App\Http\Controllers\Officer\CadetController as OfficerCadetController;
use App\Http\Controllers\Officer\DashboardController as OfficerDashboardController;
use App\Http\Controllers\Officer\ExamController as OfficerExamController;
use App\Http\Controllers\Officer\LectureMaterialController as OfficerLectureMaterialController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/enroll', function () {
    return view('enroll');
})->name('enroll');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/enroll/form', [CadetEnrollmentController::class, 'showForm'])->name('enroll.form');
    Route::post('/enroll/form', [CadetEnrollmentController::class, 'submitForm'])->name('enroll.form.submit');
    Route::get('/applicant/dashboard', [CadetEnrollmentController::class, 'applicantDashboard'])->name('cadet.applicant.dashboard');
});

// Authenticated users are redirected to their role-specific dashboard.
Route::get('/dashboard', function () {
    /** @var User $user */
    $user = Auth::user();

    return redirect($user->dashboardRoute());
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'session.timeout', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('users/create', [AdminDashboardController::class, 'createUser'])->name('users.create');
        Route::post('users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
        Route::get('users/{user}/unlock', fn () => redirect()->route('admin.dashboard'));
        Route::post('users/{user}/unlock', [AdminDashboardController::class, 'unlockAccount'])->name('users.unlock');
        Route::get('users/{user}/toggle', fn () => redirect()->route('admin.dashboard'));
        Route::post('users/{user}/toggle', [AdminDashboardController::class, 'toggleActive'])->name('users.toggle');
        // Enrollments
        Route::get('enrollments', [AdminEnrollmentController::class, 'index'])->name('enrollments.index');
        Route::post('enrollments/approve-all', [AdminEnrollmentController::class, 'approveAll'])->name('enrollments.approve_all');
        Route::get('enrollments/{user}', [AdminEnrollmentController::class, 'show'])->name('enrollments.show');
        Route::patch('enrollments/{user}/flag-medical', [AdminEnrollmentController::class, 'flagMedical'])->name('enrollments.flag_medical');
        Route::patch('enrollments/{user}/request-revision', [AdminEnrollmentController::class, 'requestRevision'])->name('enrollments.request_revision');
        Route::patch('enrollments/{user}/validate', [AdminEnrollmentController::class, 'validateEnrollment'])->name('enrollments.validate');
        Route::patch('enrollments/{user}/reject', [AdminEnrollmentController::class, 'reject'])->name('enrollments.reject');
        // Announcements
        Route::get('announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('announcements/create', [AdminAnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('announcements/{announcement}/edit', [AdminAnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::patch('announcements/{announcement}', [AdminAnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('announcements/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('announcements.destroy');
        // Attendance
        Route::get('attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/{user}', [AdminAttendanceController::class, 'show'])->name('attendance.show');
        Route::post('attendance/{user}', [AdminAttendanceController::class, 'update'])->name('attendance.update');
        // Photo management
        Route::post('users/{user}/photo', [AdminPhotoController::class, 'store'])->name('users.photo.store');
        Route::delete('users/{user}/photo', [AdminPhotoController::class, 'destroy'])->name('users.photo.destroy');

        // Examinations
        Route::resource('exams', AdminExamController::class);
        Route::post('exams/{exam}/questions', [AdminExamController::class, 'storeQuestion'])->name('exams.questions.store');
        Route::delete('exams/{exam}/questions/{question}', [AdminExamController::class, 'destroyQuestion'])->name('exams.questions.destroy');
        Route::get('exams/{exam}/results', [AdminExamController::class, 'results'])->name('exams.results');
        Route::get('exams/{exam}/results/{attempt}', [AdminExamController::class, 'showAttempt'])->name('exams.attempt');
        Route::post('exams/{exam}/results/{attempt}', [AdminExamController::class, 'gradeAttempt'])->name('exams.attempt.grade');
    });

// ── Officer routes ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'session.timeout', 'role:officer'])
    ->prefix('officer')
    ->name('officer.')
    ->group(function () {
        Route::get('dashboard', [OfficerDashboardController::class, 'index'])->name('dashboard');
        // Attendance (read-only)
        Route::get('attendance', [OfficerAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/{user}', [OfficerAttendanceController::class, 'show'])->name('attendance.show');
        Route::get('grades', [OfficerDashboardController::class, 'grades'])->name('grades');
        // Enrolled Cadets
        Route::get('cadets', [OfficerCadetController::class, 'index'])->name('cadets.index');
        Route::get('cadets/{user}', [OfficerCadetController::class, 'show'])->name('cadets.show');

        // Lecture Materials
        Route::resource('materials', OfficerLectureMaterialController::class)->except(['show']);

        // Examinations
        Route::resource('exams', OfficerExamController::class);
        Route::post('exams/{exam}/questions', [OfficerExamController::class, 'storeQuestion'])->name('exams.questions.store');
        Route::delete('exams/{exam}/questions/{question}', [OfficerExamController::class, 'destroyQuestion'])->name('exams.questions.destroy');
        Route::get('exams/{exam}/results', [OfficerExamController::class, 'results'])->name('exams.results');
        Route::get('exams/{exam}/results/{attempt}', [OfficerExamController::class, 'showAttempt'])->name('exams.attempt');
        Route::post('exams/{exam}/results/{attempt}', [OfficerExamController::class, 'gradeAttempt'])->name('exams.attempt.grade');
    });

// ── Cadet routes ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'session.timeout', 'role:cadet'])
    ->prefix('cadet')
    ->name('cadet.')
    ->group(function () {
        Route::get('dashboard', [CadetDashboardController::class, 'index'])->name('dashboard');
        Route::get('profile', [CadetDashboardController::class, 'profile'])->name('profile');
        Route::patch('profile', [CadetDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('profile/photo', [CadetPhotoController::class, 'store'])->name('profile.photo.store');
        Route::get('announcements', [CadetAnnouncementController::class, 'index'])->name('announcements');
        Route::get('attendance', [CadetAttendanceController::class, 'index'])->name('attendance');

        // Lecture Materials
        Route::get('materials', [CadetLectureMaterialController::class, 'index'])->name('materials.index');

        // Examinations
        Route::get('exams', [CadetExamController::class, 'index'])->name('exams.index');
        Route::get('exams/{exam}', [CadetExamController::class, 'show'])->name('exams.show');
        Route::post('exams/{exam}/start', [CadetExamController::class, 'start'])->name('exams.start');
        Route::get('exams/{exam}/take', [CadetExamController::class, 'take'])->name('exams.take');
        Route::post('exams/{exam}/submit', [CadetExamController::class, 'submit'])->name('exams.submit');
        Route::post('exams/{exam}/tab-switch', [CadetExamController::class, 'tabSwitch'])->name('exams.tab-switch');
    });

// ── Profile routes (accessible by all authenticated roles) ────────────────────
Route::middleware(['auth', 'session.timeout'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
