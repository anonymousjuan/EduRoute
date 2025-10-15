<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

// Livewire Components
use App\Livewire\AdminDashboard;
use App\Livewire\DeanDashboard;
use App\Livewire\ProgramHeadDashboard;
use App\Livewire\InstructorDashboard;
use App\Livewire\FacultySubjects;
use App\Http\Controllers\MySubjectsController;


// Controllers
use App\Http\Controllers\{
    GradeController,
    CurriculumController,
    InstructorController,
    StudentController,
    AccountController,
    SubjectController,
    EnrolledController,
    AnnouncementController,
    FacultySubjectController,
    WelcomeController,
    FacultyController,
    SliderController,
    StudentGradeController
};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');

// Event and hero image management (Welcome Page)
Route::delete('/welcome/hero/{id}', [WelcomeController::class, 'deleteHero'])->name('welcome.deleteHero');
Route::delete('/welcome/slider/{id}', [WelcomeController::class, 'deleteSlider'])->name('welcome.deleteSlider');
Route::delete('/welcome/event-image/{id}', [WelcomeController::class, 'deleteEventImage'])->name('welcome.deleteEventImage');
Route::put('/welcome/event-image/{id}', [WelcomeController::class, 'updateEventImage'])->name('welcome.updateEventImage');
Route::get('/events/{id}/edit', [WelcomeController::class, 'editEvent'])->name('welcome.editEvent');
Route::put('/events/{id}', [WelcomeController::class, 'updateEvent'])->name('welcome.updateEvent');

/*
|--------------------------------------------------------------------------
| Faculty Routes
|--------------------------------------------------------------------------
*/
Route::resource('faculties', FacultyController::class);
Route::get('/faculty-subjects', FacultySubjects::class)->name('faculty.subjects');
Route::get('/faculty-subjects/{subjectCode}', [FacultySubjectController::class, 'show'])->name('faculty.subject.show');
Route::post('/faculty-subjects/{subjectCode}/update', [FacultySubjectController::class, 'update'])->name('faculty.subject.update');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard & Profile
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Announcements
    Route::resource('announcements', AnnouncementController::class);

 /*
|--------------------------------------------------------------------------
| Grades
|--------------------------------------------------------------------------
*/
Route::prefix('grades')->middleware(['auth', 'verified'])->group(function () {

    // Grades main page (list of students)
    Route::get('/', [GradeController::class, 'index'])->name('grades.index');

    // Import grades
    Route::post('/import', [GradeController::class, 'import'])->name('grades.import');

    // Show a single student's grades
    Route::get('/student/{studentID}', [GradeController::class, 'show'])->name('grades.show');

    // Show all students (transcript-like view)
    Route::get('/students/{studentID}', [GradeController::class, 'showStudents'])->name('grades.students');

    // Edit grades by student and year
    Route::get('/{studentID}/edit/{yearLevel}', [GradeController::class, 'editByYear'])->name('grades.editByYear');

    // Update grades for a student
    Route::put('/{studentID}', [GradeController::class, 'update'])->name('grades.update');

    // Edit all grades of a student
    Route::get('/{studentID}/edit', [GradeController::class, 'edit'])->name('grades.edit');
    Route::get('/{studentID}/edit-year/{yearLevel}', [GradeController::class, 'editByYear'])->name('grades.editByYear');

});



    Route::resource('grades', GradeController::class);

    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('students.index');
        Route::get('/import', [StudentController::class, 'importForm'])->name('students.import.form');
        Route::post('/import', [StudentController::class, 'importCsv'])->name('students.import.csv');
        Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
        Route::post('/{student}/generate-subjects', [StudentController::class, 'generateSubjects'])->name('students.generateSubjects');
        Route::post('/student-grades/store', [StudentGradeController::class, 'store'])->name('student_grades.store');
        Route::get('/transcript/{studentID}', [TranscriptController::class, 'show'])->name('transcript.show');
        Route::get('students/students/grades/students/{studentID}', [GradeController::class, 'show'])->name('grades.students');
        // Show all students (transcript-like view)
Route::get('/grades/students/{studentID}', [GradeController::class, 'showStudents'])->name('grades.students');
Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');




        // Assign subjects
        Route::get('/{id}/assign', [StudentController::class, 'assignForm'])->name('students.assign.form');
        Route::post('/{id}/assign', [StudentController::class, 'assignSubjects'])->name('students.assign');

        // 🧾 View Transcript (points to resources/views/transcript.blade.php)
        // 🧾 View Transcript (points to resources/views/transcript.blade.php)
        Route::get('/{studentID}/transcript', [StudentController::class, 'transcript'])
        ->name('transcript.view');

    });

    Route::resource('students', StudentController::class)->except(['show']);

    /*
    |--------------------------------------------------------------------------
    | Enrolled, Curriculum, and Instructors
    |--------------------------------------------------------------------------
    */
    Route::resource('enrolled', EnrolledController::class);

    Route::get('/curriculum/import', [CurriculumController::class, 'importView'])->name('curriculum.import.view');
    Route::post('/curriculum/import-docx', [CurriculumController::class, 'importDocx'])->name('curriculum.import.docx');
    Route::resource('curriculum', CurriculumController::class);

    Route::resource('instructors', InstructorController::class);
Route::resource('enrolled', EnrolledController::class);     
  
Route::get('/curriculum/{year}/{yearLevel}/{semester}/edit', [CurriculumController::class, 'editSemester'])
     ->name('curriculum.editSemester');
Route::get('/curriculum/edit/{id}/{semester}', [CurriculumController::class, 'edit'])
    ->name('curriculum.edit');
    Route::put('/curriculum/{id}', [CurriculumController::class, 'update'])->name('curriculum.update');
Route::delete('/curriculum/delete-year/{year}', [CurriculumController::class, 'deleteYear'])->name('curriculum.deleteYear');

    /*
    |--------------------------------------------------------------------------
    | Subjects
    |--------------------------------------------------------------------------
    */
    Route::resource('subjects', SubjectController::class);
    Route::get('/my-subjects', [MySubjectsController::class, 'index'])->name('my-subjects.index');
    Route::get('/subjects/assign', [SubjectController::class, 'assignForm'])->name('subjects.assign');
    Route::post('/subjects/assign', [SubjectController::class, 'assignStore'])->name('subjects.assign.store');
    Route::get('/my-subjects/{subjectCode}/students/create', [MySubjectsController::class, 'createStudent'])
    ->name('my-subjects.students.create');






    /*
    |--------------------------------------------------------------------------
    | Accounts
    |--------------------------------------------------------------------------
    */
    Route::prefix('accounts')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('accounts.index');
        Route::get('/create', [AccountController::class, 'create'])->name('accounts.create');
        Route::post('/', [AccountController::class, 'store'])->name('accounts.store');
        Route::get('/{account}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
        Route::put('/{account}', [AccountController::class, 'update'])->name('accounts.update');
        Route::delete('/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');
        Route::patch('/{account}/toggle', [AccountController::class, 'toggle'])->name('accounts.toggle');
    });


    /*
    |--------------------------------------------------------------------------
    | Role-based Dashboards
    |--------------------------------------------------------------------------
    */
    Route::get('/admin', AdminDashboard::class)->middleware('role:super_admin')->name('admin.dashboard');
    Route::get('/dean', DeanDashboard::class)->middleware('role:dean')->name('dean.dashboard');
    Route::get('/program-head', ProgramHeadDashboard::class)->middleware('role:program_head')->name('program_head.dashboard');

    Route::middleware(['role:instructor'])->group(function () {
        Livewire::component('instructor-dashboard', InstructorDashboard::class);
        Route::view('/instructor', 'instructor.dashboard')->name('instructor.dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Program Head Welcome Page
    |--------------------------------------------------------------------------
    */
    Route::get('/programhead/welcome', [WelcomeController::class, 'edit'])->name('welcome.edit');
    Route::post('/programhead/welcome', [WelcomeController::class, 'update'])->name('welcome.update');
    Route::delete('/sliders/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');
Route::delete('/welcome/image/delete/{type}/{id}', [WelcomeController::class, 'deleteImage'])->name('welcome.deleteImage');
Route::delete('/welcome/events/{id}', [WelcomeController::class, 'destroyEvent'])->name('welcome.event.destroy');
// routes/web.php

Route::delete('/programhead/welcome/event/{id}', [WelcomeController::class, 'destroyEvent'])->name('welcome.event.destroy');

});
// Lock/Unlock Grades (for Program Heads)
Route::post('/grades/{studentID}/lock', [GradeController::class, 'lock'])->name('grades.lock');
Route::post('/grades/{studentID}/unlock', [GradeController::class, 'unlock'])->name('grades.unlock');
Route::post('/grades/lock-all', [GradeController::class, 'lockAll'])->name('grades.lockAll')->middleware('auth');
Route::post('/grades/unlock-all', [GradeController::class, 'unlockAll'])->name('grades.unlockAll')->middleware('auth');
// web.php
Route::get('/grades/{studentID}/edit-year/{yearLevel}', [GradeController::class, 'editByYear'])
    ->name('grades.editByYear')
    ->middleware(['auth', 'verified']);  // Include middleware as needed
Route::put('/grades/{studentID}', [GradeController::class, 'update'])
    ->name('grades.update')
    ->middleware(['auth', 'verified']);
Route::post('/grades/unlock-all', [GradeController::class, 'unlockAll'])->name('grades.unlockAll');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
