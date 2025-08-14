<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'name'];
    
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
