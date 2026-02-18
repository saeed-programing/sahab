<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentPhone;
use App\Models\StudentProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
use Morilog\Jalali\Jalalian;
use Spatie\SimpleExcel\SimpleExcelReader;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Student::class);

        $students = Student::orderBy('family')->get();
        return view('Students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Student::class);

        $classes = SchoolClass::all();
        return view('Students.add', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Student::class);
        $request->validate([
            'cropped_image' => 'nullable|string',
            'name' => 'required|string|min:3|max:100',
            'family' => 'required|string|min:3|max:100',
            'national_code' => 'required|string|digits:10|unique:students,national_code',
            'student_code' => 'required|string|digits:3|unique:students,student_code',
            'class_id' => 'nullable|integer|exists:school_classes,id',
            'father_name' => 'nullable|string|min:3|max:100',
            'previous_school' => 'nullable|string|min:3|max:100',
            'date_of_birth' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'class_id.integer' => 'لطفا از لیست، کلاس را انتخاب کنید',
            'class_id.exists' => 'لطفا از لیست، کلاس را انتخاب کنید',
        ]);

        if ($request->phones) {
            $request->validate([
                'phones' => ['nullable', 'array'],
                'phones.*.phone_for' => [
                    'required',
                    Rule::in(['Father', 'Mother', 'Student', 'Other']),
                ],
                'phones.*.phone_num' => [
                    'required',
                    'regex:/^09\d{9}$/',
                ],
                'phones.*.description' => [
                    function ($attribute, $value, $fail) use ($request) {

                        preg_match('/phones\.(\d+)\.description/', $attribute, $matches);
                        $index = $matches[1] ?? null;

                        if ($index === null)
                            return;

                        $phoneFor = $request->input("phones.$index.phone_for");
                        if ($phoneFor !== 'Other') {
                            return;
                        }
                        // اگر phone_for = Other → توضیح اجباری با حداقل ۳ حرف فارسی
                        if (
                            empty($value) ||
                            !preg_match('/^[\x{0600}-\x{06FF}\s]{3,}$/u', $value)
                        ) {
                            $fail('در صورت انتخاب «سایر»، توضیحات باید حداقل ۳ حرف فارسی معتبر باشد.');
                        }
                    },
                ],
            ], [
                'phones.*.phone_for.required' => 'انتخاب نوع شماره الزامی است.',
                'phones.*.phone_for.in' => 'نوع شماره معتبر نیست.',

                'phones.*.phone_num.required' => 'شماره تماس الزامی است.',
                'phones.*.phone_num.regex' => 'شماره تماس باید یک شماره موبایل ۱۱ رقمی معتبر باشد.',
            ]);
        }


        // save image
        $filename = 'default.png';
        if ($request->cropped_image) {
            try {
                $base64 = $request->cropped_image;

                preg_match('/^data:image\/(\w+);base64,/', $base64, $matches);
                $extension = $matches[1] ?? 'png';

                $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
                $image = base64_decode($image);

                $filename = 'student' . "_" . $request->national_code . '.' . $extension;

                $directory = public_path('images/students');
                $path = $directory . '/' . $filename;

                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                if (File::exists($path)) {
                    // create if not exists
                    File::ensureDirectoryExists(public_path("images/students/deleted_images"));
                    $filenameDeleted = 'deleted' . "_" . $request->national_code . "_" . time() . '.' . $extension;
                    $to = public_path('images/students/deleted_images/' . $filenameDeleted);
                    File::move($path, $to);
                }

                File::put($path, $image);
            } catch (Exception $e) {
                return redirect()->with('error', 'ذخیره سازی تصویر با مشکل مواجه شد. دوباره تلاش کنید');
            }
        }

        // insert into databases
        try {
            DB::beginTransaction();

            $studentCreated = Student::create([
                'name' => $request->name,
                'family' => $request->family,
                'class_id' => $request->class_id,
                'national_code' => $request->national_code,
                'student_code' => $request->student_code,
                'image' => $filename
            ]);

            if ($request->phones) {
                foreach ($request->phones as $phone) {
                    StudentPhone::create([
                        'student_id' => $studentCreated->id,
                        'phone_for' => $phone['phone_for'],
                        'phone_num' => $phone['phone_num'],
                        'is_just_virtual' => $phone['is_just_virtual'] ?? 0,
                        'description' => $phone['description']
                    ]);
                }
            }

            if ($request->father_name || $request->previous_school || $request->date_of_birth) {
                StudentProfile::create([
                    'student_id' => $studentCreated->id,
                    'father_name' => $request->father_name,
                    'previous_school' => $request->previous_school,
                    'date_of_birth' => $request->date_of_birth ? toGregorian($request->date_of_birth) : null,
                ]);
            }
            DB::commit();

            return redirect()->route('students.index')->with('success', 'اطلاعات دانش آموز با موفقیت ثبت شد');
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            // return redirect()->back()->with('error', "ایجاد دانش آموز با مشکل مواجه شد");
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        Gate::authorize('viewAllInformation', Student::class);

        $profile = StudentProfile::where('student_id', $student->id)->first();
        $phones = StudentPhone::where('student_id', $student->id)->get();

        $total_absences = $student->attendances()->where('status', 'absent')->count();
        $total_delays = $student->attendances()->where('status', 'late')->count();
        $total_violations = $student->violations()->count();
        $absents = $student->attendances()->where('status', 'absent')->get();
        $delays = $student->attendances()->where('status', 'late')->get();
        $violations = $student->violations;

        $generals = [
            'absents' => $absents,
            'delays' => $delays,
            'violations' => $violations,
            'total_delays' => $total_delays,
            'total_absences' => $total_absences,
            'total_violations' => $total_violations
        ];

        return view('Students.show', compact('student', 'profile', 'phones', 'generals'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $classes = SchoolClass::all();

        $profile = StudentProfile::where('student_id', $student->id)->first();
        $phones = StudentPhone::where('student_id', $student->id)->get();
        return view('Students.edit', compact('student', 'classes', 'profile', 'phones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        Gate::authorize('update', Student::class);

        $request->validate([
            'cropped_image' => 'nullable|string',
            'name' => 'required|string|min:3|max:100',
            'family' => 'required|string|min:3|max:100',
            'national_code' => "required|string|digits:10|unique:students,national_code,$student->id",
            'student_code' => "required|string|digits:3|unique:students,student_code,$student->id",
            'class_id' => 'nullable|integer|exists:school_classes,id',
            'father_name' => 'nullable|string|min:3|max:100',
            'previous_school' => 'nullable|string|min:3|max:100',
            'date_of_birth' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->phones) {
            $request->validate([
                'phones' => ['nullable', 'array'],
                'phones.*.phone_for' => [
                    'required',
                    Rule::in(['Father', 'Mother', 'Student', 'Other']),
                ],
                'phones.*.phone_num' => [
                    'required',
                    'regex:/^09\d{9}$/',
                ],
                'phones.*.description' => [
                    function ($attribute, $value, $fail) use ($request) {

                        preg_match('/phones\.(\d+)\.description/', $attribute, $matches);
                        $index = $matches[1] ?? null;

                        if ($index === null)
                            return;

                        $phoneFor = $request->input("phones.$index.phone_for");
                        if ($phoneFor !== 'Other') {
                            return;
                        }
                        // اگر phone_for = Other → توضیح اجباری با حداقل ۳ حرف فارسی
                        if (
                            empty($value) ||
                            !preg_match('/^[\x{0600}-\x{06FF}\s]{3,}$/u', $value)
                        ) {
                            $fail('در صورت انتخاب «سایر»، توضیحات باید حداقل ۳ حرف فارسی معتبر باشد.');
                        }
                    },
                ],
            ], [
                'phones.*.phone_for.required' => 'انتخاب نوع شماره الزامی است.',
                'phones.*.phone_for.in' => 'نوع شماره معتبر نیست.',

                'phones.*.phone_num.required' => 'شماره تماس الزامی است.',
                'phones.*.phone_num.regex' => 'شماره تماس باید یک شماره موبایل ۱۱ رقمی معتبر باشد.',
            ]);
        }

        // change image
        $filename = $student->image;
        if ($request->cropped_image) {
            try {
                $base64 = $request->cropped_image;

                preg_match('/^data:image\/(\w+);base64,/', $base64, $matches);
                $extension = $matches[1] ?? 'png';

                $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
                $image = base64_decode($image);

                $filename = 'student' . "_" . $request->national_code . '.' . $extension;

                $directory = public_path('images/students');
                $path = $directory . '/' . $filename;

                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                if (File::exists($path)) {
                    // create if not exists
                    File::ensureDirectoryExists(public_path("images/students/deleted_images"));

                    $filenameDeleted = 'deleted' . "_" . $request->national_code . "_" . time() . '.' . $extension;
                    $to = public_path('images/students/deleted_images/' . $filenameDeleted);
                    File::move($path, $to);
                }

                File::put($path, $image);
            } catch (Exception $e) {
                return redirect()->with('error', 'ذخیره سازی تصویر با مشکل مواجه شد. دوباره سعی کنید');
            }

        }

        // update databases
        try {
            DB::beginTransaction();

            $student->update([
                'name' => $request->name,
                'family' => $request->family,
                'class_id' => $request->class_id,
                'national_code' => $request->national_code,
                'student_code' => $request->student_code,
                'image' => $filename
            ]);

            if ($request->phones) {
                StudentPhone::where('student_id', $student->id)->delete();

                foreach ($request->phones as $phone) {
                    StudentPhone::create([
                        'student_id' => $student->id,
                        'phone_for' => $phone['phone_for'],
                        'phone_num' => $phone['phone_num'],
                        'is_just_virtual' => $phone['is_just_virtual'] ?? 0,
                        'description' => $phone['description']
                    ]);
                }
            }

            if ($request->father_name || $request->previous_school || $request->date_of_birth) {
                StudentProfile::updateOrCreate(['student_id' => $student->id], [
                    'father_name' => $request->father_name ?? null,
                    'previous_school' => $request->previous_school ?? null,
                    'date_of_birth' => $request->date_of_birth ? toGregorian($request->date_of_birth) : null,
                ]);
            } else
                StudentProfile::where('student_id', $student->id)->delete();

            DB::commit();

            return redirect()->route('students.index')->with('success', 'اطلاعات دانش آموز با موفقیت ویرایش شد');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'ویرایش اطلاعات دانش آموز با مشکل مواجه شد');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        Gate::authorize('delete', Student::class);
        try {
            $student->delete();
            File::delete('images/students/' . $student->image);
            return redirect()->route('students.index')->with('success', 'دانش آموز و تمام وابستگی ها (حضورغیاب و موارد انضباطی و...) با موفقیت حذف شدند');

        } catch (Exception $e) {
            return redirect()->route('students.index')->with('error', 'خطا! دوباره سعی کنید');
        }
    }

    public function showStudentByClass($class_id)
    {
        Gate::authorize('showStudentByClass', Student::class);

        $students = Student::where('class_id', $class_id)->get();
        return response()->json($students);
    }

    public function pendingAssignment()
    {
        $students = Student::where('class_id', null)->get();
        return response()->json($students);
    }

    public function downloadExcelTemplate()
    {
        $filePath = public_path('files/students_template.xlsx');
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'فایل پیدا نشد...');
        }

        return response()->download($filePath);
    }

    public function importStudents(request $request)
    {
        $request->validate([
            'import' => 'required|max:500|file|mimes:csv,txt|mimetypes:text/plain,text/csv,application/vnd.ms-excel',
        ]);


        $file = $request->file('import');
        $tmpPath = $file->getRealPath();
        $ext = $file->getClientOriginalExtension();
        $newPath = public_path('files/') . 'import-batch-students.' . $ext;
        if (!File::exists(public_path('files/'))) {
            File::makeDirectory(public_path('files/'), 0755, true);
        }
        if (File::exists(public_path('files/import-batch-students.' . $ext))) {
            File::delete(public_path('files/import-batch-students.' . $ext));
        }
        copy($tmpPath, $newPath);

        $rows = SimpleExcelReader::create($newPath)->useDelimiter(chr(27))->getRows()->slice(1);
        $student_count = $rows->count();
        $errors = [];

        foreach ($rows as $index => $row) {
            // foreach ($row as $key => $v) {
            //     if (is_string($v)) {
            //         $v = preg_replace('/^\x{FEFF}/u', '', $v);
            //         $v = trim($v);

            //         // فقط برای فیلدهای تلفن
            //         if (str_contains($key, 'تلفن') || str_contains($key, 'کد ملی') || str_contains($key, 'کد دانش آموزی')) {
            //             $v = str_replace(' ', '', $v);
            //         }
            //     }
            //     $row[$key] = $v === '' ? null : $v;
            // }

            $validator = Validator::make($row, [
                'نام' => 'required|string|min:3|max:100',
                'نام خانوادگی' => 'required|string|min:3|max:100',
                'کد ملی' => 'required|string|digits:10|unique:students,national_code',
                'کد دانش آموزی' => 'required|digits:3|unique:students,student_code',
                'نام کلاس' => 'required|string|exists:school_classes,name',
                'نام پدر' => 'nullable|string|min:3|max:100',
                'تاریخ تولد' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        try {
                            Jalalian::fromFormat('Y/m/d', $value)->toCarbon();
                        } catch (Exception $e) {
                            $fail('تاریخ شمسی معتبر نیست.');
                        }
                    }
                ],
                'مدرسه قبلی' => 'nullable|string|min:3|max:100',
                'تلفن پدر' => ['nullable', 'regex:/^09\d{9}$/'],
                'تلفن مادر' => ['nullable', 'regex:/^09\d{9}$/'],
                'تلفن دانش آموز' => ['nullable', 'regex:/^09\d{9}$/']
            ]);
            $validator->setAttributeNames([
                'نام' => 'نام',
                'نام خانوادگی' => 'نام خانوادگی',
                'کد ملی' => 'کد ملی',
                'کد دانش آموزی' => 'کد دانش آموزی',
                'نام کلاس' => 'نام کلاس',
                'نام پدر' => 'نام پدر',
                'تاریخ تولد' => 'تاریخ تولد',
                'مدرسه قبلی' => 'مدرسه قبلی',
                'تلفن پدر' => 'تلفن پدر',
                'تلفن مادر' => 'تلفن مادر',
                'تلفن دانش آموز' => 'تلفن دانش آموز',
            ]);

            if ($validator->fails()) {
                $errors["سطر " . ($index + 2)] = $validator->errors()->all();
            }
        }
        if ($errors) {
            $bag = new MessageBag();
            foreach ($errors as $row => $messages) {
                foreach ($messages as $message) {
                    $bag->add($row, $message);
                }
            }
            File::delete(public_path('files/import-batch-students.' . $ext));
            return back()->withErrors($bag, 'import');
        }
        try {
            DB::transaction(function () use ($rows) {

                $rows->each(function ($row) {
                    $class = SchoolClass::where('name', $row['نام کلاس'])->first();

                    $student = Student::create([
                        'name' => $row['نام'],
                        'family' => $row['نام خانوادگی'],
                        'class_id' => $class->id,
                        'national_code' => $row['کد ملی'],
                        'student_code' => $row['کد دانش آموزی'],
                    ]);
                    if (!empty($row['نام پدر']) || !empty($row['مدرسه قبلی']) || !empty($row['تاریخ تولد'])) {
                        StudentProfile::create([
                            'student_id' => $student->id,
                            'father_name' => $row['نام پدر'],
                            'previous_school' => $row['مدرسه قبلی'],
                            'date_of_birth' => toGregorian($row['تاریخ تولد'])
                        ]);
                    }
                    if (!empty($row['تلفن پدر'])) {
                        StudentPhone::create([
                            'student_id' => $student->id,
                            'phone_for' => 'Father',
                            'phone_num' => $row['تلفن پدر'],
                        ]);
                    }
                    if (!empty($row['تلفن مادر'])) {
                        StudentPhone::create([
                            'student_id' => $student->id,
                            'phone_for' => 'Mother',
                            'phone_num' => $row['تلفن مادر'],
                        ]);
                    }
                    if (!empty($row['تلفن دانش آموز'])) {
                        StudentPhone::create([
                            'student_id' => $student->id,
                            'phone_for' => 'Student',
                            'phone_num' => $row['تلفن دانش آموز'],
                        ]);
                    }
                });

            });
            File::delete(public_path('files/import-batch-students.' . $ext));
            return redirect()->route('students.index')->with('success', "تعداد $student_count دانش آموز با موفقیت افزوده شدند");
        } catch (Exception $e) {
            File::delete(public_path('files/import-batch-students.' . $ext));
            return redirect()->route('students.index')->with('error', "عملیات با شکست مواجه شد . لطفا مجددا تلاش کنید");
        }
    }
}
