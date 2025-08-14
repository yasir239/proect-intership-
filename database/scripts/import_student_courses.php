<?php

use App\Models\Student;
use App\Models\Course;
use App\Models\StudentCourse;

require __DIR__.'/../../vendor/autoload.php';
$app = require_once __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$jsonPath = __DIR__.'/student_courses.json';
if (!file_exists($jsonPath)) {
    echo "❌ لم يتم العثور على ملف student_courses.json\n";
    exit;
}

$json = file_get_contents($jsonPath);
$data = json_decode($json, true);

if (!$data) {
    echo "❌ فشل في قراءة JSON\n";
    exit;
}

foreach ($data as $username => $courseCodes) {
    echo "🔍 الطالب: $username\n";
    $student = Student::where('username', $username)->first();

    if (!$student) {
        echo "❌ الطالب غير موجود في قاعدة البيانات: $username\n";
        continue;
    }

    foreach ($courseCodes as $code) {
        echo "➡️ محاولة ربط $username مع $code\n";
        $course = Course::where('code', $code)->first();

        if (!$course) {
            echo "⚠️ الكورس غير موجود: $code\n";
            continue;
        }

        $section = $course->sections()->first();
        if (!$section) {
            echo "⚠️ لا توجد شُعب للكورس $code\n";
            continue;
        }

        StudentCourse::updateOrCreate([
            'student_id' => $student->id,
            'section_id' => $section->id,
        ]);

        echo "✅ تم الربط بنجاح: $username - $code (شعبة {$section->id})\n";
    }
}
