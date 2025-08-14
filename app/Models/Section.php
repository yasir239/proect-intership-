<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['course_id', 'day', 'start_time', 'end_time'];
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class);
    }
}
