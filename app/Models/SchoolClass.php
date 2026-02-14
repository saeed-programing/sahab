<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function getLevelLabelAttribute()
    {
        return [
            'seven' => 'هفتم',
            'eight' => 'هشتم',
            'nine' => 'نهم',
        ][$this->level ?? 'نامشخص'];
    }
}
