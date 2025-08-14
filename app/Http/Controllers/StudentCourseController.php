<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentCourse;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;

class StudentCourseController extends Controller
{
    // Register a section for the student, prevent time conflicts
    public function register(Request $request)
    {
        $studentId = $request->user() ? $request->user()->id : 1; // For demo, use 1
        $sectionId = $request->input('section_id');
        $section = Section::findOrFail($sectionId);

        // Check for time conflict
        $conflict = StudentCourse::where('student_id', $studentId)
            ->whereHas('section', function($q) use ($section) {
                $q->where('day', $section->day)
                  ->where(function($q2) use ($section) {
                      $q2->where(function($q3) use ($section) {
                          $q3->where('start_time', '<', $section->end_time)
                              ->where('end_time', '>', $section->start_time);
                      });
                  });
            })->exists();
        if ($conflict) {
            return response()->json(['success'=>false, 'message'=>'يوجد تعارض في الوقت مع مقرر آخر.'], 409);
        }

        $studentCourse = StudentCourse::firstOrCreate([
            'student_id' => $studentId,
            'section_id' => $sectionId,
        ]);
        return response()->json(['success'=>true]);
    }

    // Remove a section from the student's schedule
    public function remove(Request $request)
    {
        $studentId = $request->user() ? $request->user()->id : 1;
        $sectionId = $request->input('section_id');
        StudentCourse::where('student_id', $studentId)->where('section_id', $sectionId)->delete();
        return response()->json(['success'=>true]);
    }
}
