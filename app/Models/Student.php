<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function phones()
    {
        return $this->hasMany(StudentPhone::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function violations()
    {
        return $this->hasMany(StudentViolation::class);
    }
}



