# Examinations & Lecture Materials Module

This document outlines the implementation details for the Examinations and Lecture Materials features in the 133rd NROTC Portal.

## Overview
The system provides a comprehensive academic module allowing Officers to upload lecture materials and construct timed, secure examinations for Cadets.

## Roles & Permissions

### Officer
- **Lecture Materials:** Can upload, edit, and delete lecture files (PDFs, DOCX, images, etc.).
- **Examinations:** Has full access to the Exam Builder. Can:
  - Create and manage exams.
  - Set time limits, grading levels, and schedule (start/end dates).
  - Enable security features (Prevent back navigation, Auto-submit on tab switch).
  - Add multiple types of questions (Multiple Choice, Identification, Enumeration, Essay).
  - Organize exams into parts (Part 1, Part 2).
  - Manually grade essay questions for cadets.
  - View overall exam statistics and individual cadet attempts.
  - Publish/Unpublish exams.

### Cadet
- **Lecture Materials:** Can view and download published lecture materials.
- **Examinations:** Can view open examinations.
  - Must take the exam within the allotted time limit.
  - Will be monitored for tab-switching if the security setting is enabled.
  - Can view their final score once graded (or immediately if auto-graded).

### Administrator
- Default administrators do not manage academic content directly. They handle user accounts, verification, and global announcements.

## Security Features (Anti-Cheating)
1. **Time Limits:** Exams are strictly timed via an Alpine.js countdown.
2. **Prevent Back Navigation:** Optional setting that disables the ability to return to a previous question.
3. **Tab Switch Detection:** Optional setting that monitors `document.visibilityState`. If a cadet leaves the exam tab, the attempt is flagged, and the exam can be configured to automatically submit on the first violation.

## Application Architecture

### Models
- `Exam`: Core model storing exam configuration (title, time limit, security settings).
- `ExamQuestion`: Stores question text, type, options (for multiple choice), and correct answers.
- `ExamAttempt`: Tracks a cadet's attempt, including start time, end time, total score, and tab switch count.
- `ExamAnswer`: Stores the individual answers submitted by the cadet for grading.
- `LectureMaterial`: Stores file paths, titles, and descriptions for uploaded materials.

### Routes
**Officer Routes (`/officer`)**
- `officer.exams.*` (Resource routes for managing exams)
- `officer.exams.results` (View cadet scores)
- `officer.exams.attempt` (Grade a specific attempt)
- `officer.materials.*` (Resource routes for managing lecture materials)

**Cadet Routes (`/cadet`)**
- `cadet.exams.index`, `cadet.exams.show` (View available exams)
- `cadet.exams.take`, `cadet.exams.submit` (Take and submit an exam)
- `cadet.materials.index` (View available materials)

### Views & Navigation
The primary navigation is located in the **master layout file** (`resources/views/layouts/app.blade.php`), which renders the sidebar for all roles. 
- Officer Sidebar includes: `Lecture Materials`, `Examinations`, and `Create Exam`.
- Cadet Sidebar includes: `Lecture Materials`, `Examinations`.
*Note: Individual views like `dashboard.blade.php` contain dead `@section('sidebar-nav')` code which is superseded by the master layout.*
