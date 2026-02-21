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
            ->get()
            ->map(function ($student) {
                if (empty($student->date_of_birth)) {
                    return null;
                }

                $jalali = Jalalian::fromDateTime($student->date_of_birth);

                $student->jalali_month = $jalali->getMonth();
                $student->jalali_day = $jalali->getDay();

                return $student;
            })
            ->filter(function ($student) {
                return $student && $student->jalali_month === Jalalian::now()->getMonth();
            })
            ->sortBy('jalali_day')
            ->values();
        // Students Of unknown Class
        $unknownClass = Student::where('class_id', null)->get();

        // Students Of unknown Attendance on the school_class
        $unknownAttendance = Attendance::with('student.schoolClass.teacher')
            ->where('status', 'unknown')
            ->whereRelation('student.schoolClass.teacher', 'id', Auth::user()->id)
            ->orderByDesc('date')
            ->get();

        return view('dashboard', compact('studentsThisMonth', 'unknownClass', 'unknownAttendance'));
    }
}
