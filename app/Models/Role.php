<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function getRoleLabelAttribute()
    {
        return [
            'super_admin' => 'مدیر سایت',
            'admin' => 'مدیر',
            'dvisor' => 'استاد راهنما',
            'ExecutiveOfficer' => 'معاون اجرایی',
            'EducationOfficer' => 'معاون آموزش',
            'AttendanceOfficer' => 'مسئول حضور/غیاب',
        // 'CulturalOfficer' => 'معاون پرورشی',
        // 'MediaCoordinator' => 'مسئول رسانه',
        // 'teacher' => 'معلم',
        // 'student' => 'دانش آموز',
        // 'parent' => 'والدین',
        ][$this->title ?? 'نامشخص'];
    }
}
