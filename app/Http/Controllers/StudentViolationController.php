<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentViolation;
use App\Models\ViolationTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Throwable;

class StudentViolationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', StudentViolation::class);

        $violations_count = StudentViolation::select('case_id')
            ->with('case')
            ->groupBy('case_id')
            ->selectRaw('count(*) as total, case_id')
            ->get();
        $violations = StudentViolation::with('case')->get();

        return view('Violations.index', compact('violations_count', 'violations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', StudentViolation::class);

        $students = Student::orderBy('family')->get();
        $cases = ViolationTitle::all();
        return view('Violations.add', compact('students', 'cases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', StudentViolation::class);

        $request->validate([
            'student_id' => 'required|exists:students,id|integer',
            'date' => 'required|date',
            'case_id' => 'required|integer|exists:violation_titles,id',
            'description' => ['required', 'regex:/^[آ-یءئؤإأۀە\s]+$/u', 'min:5']
        ], [
            'student_id.required' => 'این فیلد نمی تواند خالی باشد',
            'student_id.integer' => 'نامعتبر',
            'student_id.exists' => 'دانش آموز یافت نشد',
            'case_id.required' => 'این فیلد نمی تواند خالی باشد',
            'case_id.integer' => 'نامعتبر',
            'case_id.exists' => 'این مورد قبلا تعریف نشده است. لطفا از قسمت مدیریت اقدام کنید',
        ]);
        try {
            StudentViolation::create([
                'student_id' => $request->student_id,
                'date' => toGregorian($request->date),
                'case_id' => $request->case_id,
                'description' => $request->description,
                'registered_by' => auth()->user()->id
            ]);
            return redirect()->route('student-violations.index')->with('success', "ثبت با موفقیت انجام شد");
        } catch (Throwable $e) {
            $code = $e->getCode();
            return redirect()->route('student-violations.index')->with('error', "خطا در ثبت! <br> کد خطا: $code");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentViolation $student_violation)
    {
        Gate::authorize('update', StudentViolation::class);

        $cases = ViolationTitle::all();
        return view('Violations.edit', compact('student_violation', 'cases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentViolation $student_violation)
    {
        Gate::authorize('update', StudentViolation::class);

        $request->validate([
            'date' => 'required|date',
            'case_id' => 'required|integer|exists:violation_titles,id',
            'description' => ['required', 'regex:/^[آ-یءئؤإأۀە\s]+$/u', 'min:5']
        ], [
            'case_id.required' => 'این فیلد نمی تواند خالی باشد',
            'case_id.integer' => 'نامعتبر',
            'case_id.exists' => 'این مورد قبلا تعریف نشده است. لطفا از قسمت مدیریت اقدام کنید',
        ]);

        try {
            $student_violation->update([
                'date' => toGregorian($request->date),
                'case_id' => $request->case_id,
                'description' => $request->description,
                'registered_by' => auth()->user()->id
            ]);
            return redirect()->back()->with('success', "ویرایش با موفقیت انجام شد");
        } catch (Throwable $e) {
            $code = $e->getCode();
            return redirect()->back()->with('error', "خطا در ثبت! <br> کد خطا: $code");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentViolation $student_violation)
    {
        Gate::authorize('delete', StudentViolation::class);

        try {
            $student_violation->delete();
            return redirect()->route('student-violations.index')->with('success', 'حذف مورد انضباطی با موفقیت انجام شد');
        } catch (Throwable $e) {
            $code = $e->getCode();
            return redirect()->route('student-violations.index')->with('error', "حذف مورد انضباطی با خطا مواجه شد<br>کد خطا: $code");
        }
    }

    public function report()
    {
        Gate::authorize('report', StudentViolation::class);

        $allViolations = StudentViolation::with(['student', 'case', 'register'])->get();
        return view('Violations.reportAll', compact('allViolations'));
    }
}
