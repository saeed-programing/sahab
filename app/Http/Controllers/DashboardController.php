<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class DashboardController extends Controller
{
    public function index()
    {
        // Births Of The Month
        $studentsThisMonth = StudentProfile::with('student')
            ->orderBy('date_of_birth')
            ->get()
            ->filter(function ($student) {
                if (empty($student->date_of_birth))
                    return false;

                $jalali = Jalalian::fromDateTime($student->date_of_birth);
                return $jalali->getMonth() === Jalalian::now()->getMonth();
            });
        // Students Of unknown Class
        $unknownClass = Student::where('class_id', null)->get();

        // Students Of unknown Attendance on the school_class
        $unknownAttendance = Attendance::with('student.schoolClass.teacher')
            ->where('status', 'unknown')
            ->whereRelation('student.schoolClass.teacher', 'id', Auth::user()->id)
            ->get();

        return view('dashboard', compact('studentsThisMonth', 'unknownClass', 'unknownAttendance'));
    }
}
