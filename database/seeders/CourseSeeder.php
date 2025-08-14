<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Section;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Ahmed's Computer Science Courses
        // CS101 - Introduction to Programming
        $course = Course::create(['code' => 'CS101', 'name' => 'مقدمة في علوم الحاسب']);
        $course->sections()->create(['id' => 1001, 'day' => 'Sunday', 'start_time' => '08:00:00', 'end_time' => '09:30:00']);
        $course->sections()->create(['id' => 1002, 'day' => 'Monday', 'start_time' => '11:00:00', 'end_time' => '12:30:00', 'is_closed' => true]);
        
        // CS302 - مدخل إلى هندسة البرمجيات
        $course = Course::create(['code' => 'CS302', 'name' => 'مدخل إلى هندسة البرمجيات']);
        $course->sections()->create(['id' => 1101, 'day' => 'Monday', 'start_time' => '09:00:00', 'end_time' => '10:30:00']);
        $course->sections()->create(['id' => 1102, 'day' => 'Wednesday', 'start_time' => '13:00:00', 'end_time' => '14:30:00']);
        
        // COE401 - التصميم المنطقي
        $course = Course::create(['code' => 'COE401', 'name' => 'التصميم المنطقي']);
        $course->sections()->create(['id' => 1201, 'day' => 'Tuesday', 'start_time' => '08:00:00', 'end_time' => '09:30:00']);
        $course->sections()->create(['id' => 1202, 'day' => 'Thursday', 'start_time' => '11:00:00', 'end_time' => '12:30:00', 'is_closed' => true]);

        // CS204 - هياكل البيانات
        $course = Course::create(['code' => 'CS204', 'name' => 'هياكل البيانات']);
        $course->sections()->create(['id' => 1301, 'day' => 'Sunday', 'start_time' => '11:00:00', 'end_time' => '12:30:00']);
        $course->sections()->create(['id' => 1302, 'day' => 'Tuesday', 'start_time' => '13:00:00', 'end_time' => '14:30:00']);

        // CS303 - خوارزميات
        $course = Course::create(['code' => 'CS303', 'name' => 'خوارزميات']);
        $course->sections()->create(['id' => 1401, 'day' => 'Monday', 'start_time' => '13:00:00', 'end_time' => '14:30:00']);
        $course->sections()->create(['id' => 1402, 'day' => 'Wednesday', 'start_time' => '09:00:00', 'end_time' => '10:30:00', 'is_closed' => true]);

        // CS304 - أنظمة التشغيل
        $course = Course::create(['code' => 'CS304', 'name' => 'أنظمة التشغيل']);
        $course->sections()->create(['id' => 1501, 'day' => 'Thursday', 'start_time' => '08:00:00', 'end_time' => '09:30:00']);
        $course->sections()->create(['id' => 1502, 'day' => 'Tuesday', 'start_time' => '11:00:00', 'end_time' => '12:30:00']);

        // Saeed's Medical Courses
        // MED101 - مقدمة في الطب
        $course = Course::create(['code' => 'MED101', 'name' => 'مقدمة في الطب']);
        $course->sections()->create(['id' => 2001, 'day' => 'Sunday', 'start_time' => '08:00:00', 'end_time' => '09:30:00']);
        $course->sections()->create(['id' => 2002, 'day' => 'Tuesday', 'start_time' => '11:00:00', 'end_time' => '12:30:00']);

        // ANAT201 - علم التشريح البشري
        $course = Course::create(['code' => 'ANAT201', 'name' => 'علم التشريح البشري']);
        $course->sections()->create(['id' => 2101, 'day' => 'Monday', 'start_time' => '09:00:00', 'end_time' => '10:30:00']);
        $course->sections()->create(['id' => 2102, 'day' => 'Wednesday', 'start_time' => '13:00:00', 'end_time' => '14:30:00', 'is_closed' => true]);

        // MED301 - الطب الباطني
        $course = Course::create(['code' => 'MED301', 'name' => 'الطب الباطني']);
        $course->sections()->create(['id' => 2201, 'day' => 'Tuesday', 'start_time' => '08:00:00', 'end_time' => '09:30:00']);
        $course->sections()->create(['id' => 2202, 'day' => 'Thursday', 'start_time' => '11:00:00', 'end_time' => '12:30:00']);

        // PHYSI202 - علم الفسيولوجيا
        $course = Course::create(['code' => 'PHYSI202', 'name' => 'علم الفسيولوجيا']);
        $course->sections()->create(['id' => 2301, 'day' => 'Sunday', 'start_time' => '11:00:00', 'end_time' => '12:30:00']);
        $course->sections()->create(['id' => 2302, 'day' => 'Wednesday', 'start_time' => '09:00:00', 'end_time' => '10:30:00']);
    }
}
