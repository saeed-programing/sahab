<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentViolation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function case()
    {
        return $this->belongsTo(ViolationTitle::class, 'case_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function register()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}
