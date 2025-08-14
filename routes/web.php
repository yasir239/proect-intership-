<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Models\Course;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentCourse;

Route::redirect('/', '/login');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/schedule', function (Request $request) {
    if (!session()->has('student_id')) {
        return redirect('/login');
    }

    $student = Student::find(session('student_id'));

    // الأقسام المسجلة لهذا الطالب
    $registeredSections = Section::with('course')
        ->whereIn('id', StudentCourse::where('student_id', $student->id)->pluck('section_id'))
        ->get();

    // كل الكورسات + الشعب
    $courses = Course::with('sections')->get();

    return view('schedule', compact('courses', 'registeredSections'));
})->name('schedule');

// Schedule registration/removal
Route::post('/schedule/register', function (Request $request) {
    $studentId = session('student_id');
    $sectionId = $request->section_id;

    $exists = \App\Models\StudentCourse::where('student_id', $studentId)
        ->where('section_id', $sectionId)->exists();

    if ($exists) {
        return response()->json(['message' => 'مسجل مسبقًا'], 400);
    }

    \App\Models\StudentCourse::create([
        'student_id' => $studentId,
        'section_id' => $sectionId,
    ]);

    return response()->json(['message' => 'تم التسجيل']);
});

// حذف القسم
Route::post('/schedule/remove', function (Request $request) {
    $studentId = session('student_id');
    $sectionId = $request->section_id;

    \App\Models\StudentCourse::where('student_id', $studentId)
        ->where('section_id', $sectionId)->delete();

    return response()->json(['message' => 'تم الحذف']);
});

// Add schedule and registration routes as needed

// Student::all();
