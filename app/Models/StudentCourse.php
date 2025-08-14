<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    protected $fillable = ['student_id', 'section_id'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
