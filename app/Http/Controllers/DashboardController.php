<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentProfile;
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
        $unknownStudents = Student::where('class_id', null)->get();

        return view('dashboard', compact('studentsThisMonth', 'unknownStudents'));
    }
}
