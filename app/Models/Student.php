<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Student extends Model
{
    protected $fillable = ['name', 'username', 'password'];

    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class);
    }

    /**
     * Automatically hash password when set
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
