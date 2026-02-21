<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $report = Attendance::select(
            'date',
            DB::raw('SUM(CASE WHEN status = "unknown" THEN 1 ELSE 0 END) as unknown'),
            DB::raw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent'),
            DB::raw('SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late'),
            DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present')
        )->groupBy('date')->orderByDesc('date')->get();

        $unknownStudents = Attendance::where('status', 'unknown')->get();
        return view('Attendances.index', compact('report', 'today', 'unknownStudents'));
    }
    public function unknownStudents($date)
    {
        $unknownStudents = Attendance::where('date', $date)->where('status', 'unknown')->with(['student', 'student.schoolClass'])->get();
        return response()->json($unknownStudents);
    }
    public function absenceRegistration($date)
    {
        Gate::authorize('absenceRegistration', Attendance::class);

        $validation = Validator::make(['date' => $date], ['date' => 'required|date']);
        if ($validation->fails())
            return redirect()->route('attendances.index')->with('error', "تاریخ نامعتبر");

        Attendance::where('date', $date)
            ->where('status', 'unknown')
            ->update(['status' => 'absent']);

        return redirect()->route('attendances.index')->with('success', "غیبت‌های تاریخ " . toJalali($date) . " با موفقیت ثبت شد.");
    }
    public function addNewDay($day)
    {
        Gate::authorize('addNewDay', Attendance::class);

        $validator = Validator::make(['date' => $day], [
            'date' => 'required|date|date_equals:today|unique:attendances,date',
        ], [
            'date.required' => 'خطا در ارسال تاریخ',
            'date.date' => 'فرمت تاریخ صحیح نیست',
            'date.date_equals' => 'تاریخ باید برای امروز باشد',
            'date.unique' => 'این تاریخ قبلا ایجاد شده است'
        ]);
        if ($validator->fails()) {
            return redirect()->route('attendances.index')->with('error', $validator->errors()->first());
        }
        if (Student::all()->count() === 0) {
            return redirect()->route('attendances.index')->with('error', 'هیچ دانش آموزی ثبت نشده. ابتدا از قسمت مدیریت، حداقل یک دانش آموز اضافه کنید');
        }

        $students = Student::all();
        foreach ($students as $student) {
            Attendance::create([
                'student_id' => $student->id,
                'date' => $day,
                'status' => 'unknown',
                'registered_by' => Auth::user()->id
            ]);
        }

        return redirect()->back()->with('success', 'عملیات موفقیت آمیز بود');



    }
    public function deleteDay($day)
    {
        Gate::authorize('deleteDay', Attendance::class);

        $validator = validator::make(['day' => $day], [
            'day' => 'required|exists:attendances,date'
        ]);

        if ($validator->fails())
            return redirect()->back()->with('error', $validator->errors()->first());

        Attendance::where('date', $day)->delete();
        return redirect()->back()->with('success', 'روز با موفقیت حذف شد');
    }
    public function registrationByNationalCode($day)
    {
        $validator = Validator::make(['day' => $day], ['day' => 'required|date|exists:attendances,date'], ['day.exists' => 'روز انتخابی به عنوان روز درسی معرفی نشده است']);
        if ($validator->fails())
            return redirect()->route('attendances.index')->with('error', $validator->errors()->first());

        if (!Carbon::parse($day, 'Asia/Tehran')->isSameDay(now('Asia/Tehran')))
            Gate::authorize('attendancePreviousDay', User::class);

        $count = Attendance::where('date', $day)->where('status', 'unknown')->count();
        if ($count === 0)
            return redirect()->route('attendances.index')->with('success', "تمامی دانش آموزان در روز " . toJalali($day) . " حضور غیاب شده اند.");
        return view('Attendances.registrationByNationalCode', compact('day'));
    }
    public function searchStudentByNationalCode(Request $request)
    {
        $query = Student::query();
        if (!$request->national_id)
            return response()->json(['status' => 'error', 'message' => 'کدملی ارسال نشده']);


        $query->where('national_code', $request->national_id);
        $student = $query->first();
        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'دانش آموزی با این مشخصات یافت نشد']);
        }

        return response()->json(['status' => 'success', 'user' => $student]);
    }
    public function registrationByStudentCode($day)
    {
        $validator = Validator::make(['day' => $day], ['day' => 'required|date|exists:attendances,date'], ['day.exists' => 'روز انتخابی به عنوان روز درسی معرفی نشده است']);
        if ($validator->fails())
            return redirect()->route('attendances.index')->with('error', $validator->errors()->first());

        if (!Carbon::parse($day, 'Asia/Tehran')->isSameDay(now('Asia/Tehran')))
            Gate::authorize('attendancePreviousDay', User::class);

        $count = Attendance::where('date', $day)->where('status', 'unknown')->count();
        if ($count === 0)
            return redirect()->route('attendances.index')->with('success', "تمامی دانش آموزان در روز " . toJalali($day) . " حضور غیاب شده اند.");
        return view('Attendances.registrationByStudentCode', compact('day'));
    }
    public function searchStudentByStudentCode(Request $request)
    {
        $query = Student::query();
        if (!$request->student_code)
            return response()->json(['status' => 'error', 'message' => 'کد دانش آموزی ارسال نشده']);

        $query->where('student_code', $request->student_code);
        $student = $query->first();
        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'دانش آموزی با این مشخصات یافت نشد']);
        }

        return response()->json(['status' => 'success', 'user' => $student]);
    }
    public function confirmStudent(Request $request)
    {
        if ($request->has('delay') && $request->delay <= 10) {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|integer|exists:students,id',
                'date' => 'required|date',
                'delay' => 'required|integer',
                // 'description' => 'required',
                'is_excused' => 'required'
            ]);
            if ($validator->fails())
                return redirect()->back()->with('error', $validator->errors()->first());


            if (Attendance::where('student_id', $request->student_id)->where('date', $request->date)->where('status', '!=', 'unknown')->exists())
                return redirect()->back()->with('error', 'وضعیت حضور دانش آموز در این روز قبلا تعیین شده است.');

            $registration = Attendance::where('student_id', $request->student_id)->where('date', $request->date)->update([
                'delay' => $request->delay,
                'is_excused' => $request->is_excused == 'true' ? true : false,
                'status' => 'late',
                'registered_by' => Auth::user()->id
            ]);

            if (!$registration)
                return redirect()->back()->with('error', 'خطا. دوباره سعی کنید');

            return redirect()->back()->with('warning', 'ثبت تاخیر موفقیت آمیز بود');
        }


        if ($request->has('delay') && $request->delay > 10) {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|integer|exists:students,id',
                'date' => 'required|date',
            ]);
            if ($validator->fails())
                return redirect()->back()->with('error', $validator->errors()->first());


            if (Attendance::where('student_id', $request->student_id)->where('date', $request->date)->where('status', '!=', 'unknown')->exists())
                return redirect()->back()->with('error', 'وضعیت حضور دانش آموز در این روز قبلا تعیین شده است.');

            $registration = Attendance::where('student_id', $request->student_id)->where('date', $request->date)->update([
                'delay' => $request->delay,
                'is_excused' => $request->is_excused == 'true' ? true : false,
                'status' => 'late',
                'description' => $request->description,
                'registered_by' => Auth::user()->id
            ]);

            if (!$registration)
                return redirect()->back()->with('error', 'خطا. دوباره سعی کنید');

            return redirect()->back()->with('warning', 'ثبت تاخیر موفقیت آمیز بود');
        }
        if (!$request->has('delay')) {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|integer|exists:students,id',
                'date' => 'required|date',
            ]);
            if ($validator->fails())
                return redirect()->back()->with('error', $validator->errors()->first());


            if (Attendance::where('student_id', $request->student_id)->where('date', $request->date)->where('status', '!=', 'unknown')->exists())
                return redirect()->back()->with('error', 'وضعیت حضور دانش آموز در این روز قبلا تعیین شده است.');

            $registration = Attendance::where('student_id', $request->student_id)->where('date', $request->date)->update([
                'status' => 'present',
                'registered_by' => Auth::user()->id
            ]);

            if (!$registration)
                return redirect()->back()->with('error', 'خطا. دوباره سعی کنید');

            return redirect()->back()->with('success', 'ثبت حضور موفقیت آمیز بود');
        }
    }
    public function reportIndex()
    {
        Gate::authorize('reportIndex', Attendance::class);

        $attendances = Attendance::with(['student', 'register'])->get();
        return view('Attendances.reportAll', compact('attendances'));
    }
    public function sendAbsenceReport($date)
    {
        Gate::authorize('sendAbsenceReport', Attendance::class);

        $validator = Validator::make(["date" => $date], [
            'date' => 'required|date|exists:attendances,date'
        ], [
            'date.exists' => 'تاریخ مورد نظر وجود ندارد',
            'date.date' => 'فرمت تاریخ ارسالی اشتباه است',
            'date.required' => 'تاریخی ارسال نشده است'
        ]);

        if ($validator->fails())
            return redirect()->back()->with('error', $validator->errors()->first());

        $Absences = Attendance::with('student')->where('date', $date)->where('status', 'absent')->get();

        if ($Absences->isEmpty())
            return redirect()->back()->with('error', 'دانش آموزی غائبی برای امروز یافت نشد.');

        $dateJalali = toJalali($date);
        $message = "📋 غائبین $dateJalali :" . "\n \n" . $Absences->map(fn($a, $i) => ($i + 1) . ' - ' . $a->student->family . ' - ' . $a->student->name)->implode("\n");

        $sendStatus = sendMessageByEitaa("غائبین $dateJalali", $message);
        if ($sendStatus['ok'] === false) {
            $error_code = $sendStatus['error_code'];
            $description = $sendStatus['description'];
            return redirect()->back()->with('error', "خطا در ارسال: <br> کد خطا: $error_code <br>متن خطا: $description");
        }

        return redirect()->back()->with('success', "گزارش غائبین $dateJalali با موفقیت در کانال بارگزاری شد");
    }
    public function editAttendance($date, $student_id)
    {
        Gate::authorize('editAttendance', Attendance::class);

        $validator = validator::make(["date" => $date, "student_id" => $student_id], [
            'date' => 'required|date|exists:attendances,date',
            'student_id' => 'required|integer|exists:attendances,student_id'
        ], [
            'date.required' => 'تاریخی ارسال نشده است',
            'date.date' => 'فرمت تاریخ ارسالی صحیح نیست',
            'date.exists' => 'این تاریخ قبلا به عنوان روز درسی ایجاد نشده است',
            'student_id.required' => 'کد دانش آموز ارسال نشده است',
            'student_id.integer' => 'فرمت ارسالی کد دانش آموز صحیح نیست',
            'student_id.exists' => 'برای این دانش آموز، در این تاریخ، روز درسی ثبت نشده است',
        ]);
        if ($validator->fails())
            return redirect()->route('attendances.index')->with('error', $validator->errors()->first());

        $attendance = Attendance::where('date', $date)->where('student_id', $student_id)->with(['register', 'student', 'student.schoolClass'])->first();
        return view('Attendances.edit', compact('attendance'));
    }
    public function editAttendancePost(Request $request)
    {
        Gate::authorize('editAttendance', Attendance::class);

        $returnUrl = $request->return_url;

        $validator = Validator::make(
            $request->all(),
            [
                'attendance_id' => 'required|integer|exists:attendances,id',
                'status' => 'required|in:unknown,present,late,absent'
            ],
            [
                'attendance_id.required' => 'خطا در ارسال شناسه',
                'attendance_id.integer' => 'شناسه معتبر نیست',
                'attendance_id.exists' => 'حضور غیاب یافت نشد',
                'status.required' => 'لطفا وضعیت حضور را انتخاب کنید',
                'status.in' => 'وضعیت انتخاب شده معتبر نیست',
            ]
        );

        if ($validator->fails())
            return redirect()->back()->with('error', $validator->errors()->first());

        $attendance = Attendance::findOrFail($request->attendance_id);

        if ($request->status == 'late') {
            $request->validate(
                [
                    'is_excused' => 'required|in:0,1',
                    'delay' => 'required|integer|min:1',
                    'description' => 'required|min:5'
                ],
                [
                    'is_excused.required' => 'تعیین وضعیت موجه بودن الزامی است',
                    'is_excused.in' => 'نامعتبر',
                    'delay.required' => 'مقدار تاخیر الزامی است',
                    'delay.integer' => 'نامعتبر',
                    'delay.min' => 'حداقل تاخیر، 1 دقیقه است',
                    'description.required' => 'درصورت غیبت/تاخیر توضیحات ضروری است',
                    'description.min' => 'حداقل توضیحات، 5 کاراکتر است'
                ]
            );

            try {
                $attendance->update([
                    'status' => $request->status,
                    'delay' => $request->delay,
                    'is_excused' => $request->is_excused,
                    'description' => $request->description,
                    'registered_by' => Auth::user()->id,
                ]);
                return $returnUrl ? redirect($returnUrl)->with('success', 'وضعیت حضور ' . $attendance->student->family . ' در تاریخ ' . toJalali($attendance->date) . ' با موفقیت تغییر کرد')
                    : redirect()->route('dashboard')->with('success', 'وضعیت حضور ' . $attendance->student->family . ' در تاریخ ' . toJalali($attendance->date) . ' با موفقیت تغییر کرد');
            } catch (Throwable $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        if ($request->status == 'absent') {
            $request->validate(
                [
                    'is_excused' => 'required|integer',
                    'description' => 'required|min:5'
                ],
                [
                    'is_excused.required' => 'تعیین وضعیت موجه بودن الزامی است',
                    'is_excused.integer' => 'نامعتبر',
                    'description.required' => 'درصورت غیبت/تاخیر توضیحات ضروری است',
                    'description.min' => 'حداقل توضیحات، 5 کاراکتر است'
                ]
            );

            try {
                $attendance->update([
                    'status' => $request->status,
                    'is_excused' => $request->is_excused,
                    'description' => $request->description,
                    'registered_by' => Auth::user()->id,
                ]);
                return $returnUrl ? redirect($returnUrl)->with('success', 'وضعیت حضور ' . $attendance->student->family . ' در تاریخ ' . toJalali($attendance->date) . ' با موفقیت تغییر کرد')
                    : redirect()->route('dashboard')->with('success', 'وضعیت حضور ' . $attendance->student->family . ' در تاریخ ' . toJalali($attendance->date) . ' با موفقیت تغییر کرد');
            } catch (Throwable $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        if ($request->status == 'present') {
            try {
                $attendance->update([
                    'status' => $request->status,
                    'registered_by' => Auth::user()->id,
                ]);
                return $returnUrl ? redirect($returnUrl)->with('success', 'وضعیت حضور ' . $attendance->student->family . ' در تاریخ ' . toJalali($attendance->date) . ' با موفقیت تغییر کرد')
                    : redirect()->route('dashboard')->with('success', 'وضعیت حضور ' . $attendance->student->family . ' در تاریخ ' . toJalali($attendance->date) . ' با موفقیت تغییر کرد');
            } catch (Throwable $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        if ($request->status == 'unknown') {
            try {
                $attendance->update([
                    'status' => $request->status,
                    'registered_by' => Auth::user()->id,
                ]);

                return $returnUrl ? redirect($returnUrl)->with('success', 'وضعیت حضور ' . $attendance->student->family . ' در تاریخ ' . toJalali($attendance->date) . ' با موفقیت تغییر کرد')
                    : redirect()->route('dashboard')->with('success', 'وضعیت حضور ' . $attendance->student->family . ' در تاریخ ' . toJalali($attendance->date) . ' با موفقیت تغییر کرد');
            } catch (Throwable $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }
}
