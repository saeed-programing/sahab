<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Throwable;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', SchoolClass::class);

        $schoolClasses = SchoolClass::all();
        $unknownStudents = Student::where('class_id', null)->get();
        return view('SchoolClasses.index', compact('schoolClasses', 'unknownStudents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', SchoolClass::class);

        $teachers = User::all();
        return view('SchoolClasses.add', compact('teachers'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('update', SchoolClass::class);

        $request->validate([
            'name' => ['required', 'regex:/^[آابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی\s]+$/u', 'unique:school_classes,name'],
            'teacher' => 'required|exists:users,id',
            'level' => 'required|in:seven,eight,nine'
        ]);

        SchoolClass::create([
            'name' => $request->name,
            'teacher_id' => $request->teacher,
            'level' => $request->level,
        ]);

        return redirect()->route('classes.index')->with('success', 'کلاس با موفقیت افزوده شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolClass $schoolClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolClass $class)
    {
        Gate::authorize('update', SchoolClass::class);

        $teachers = User::all();
        return view('SchoolClasses.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolClass $class)
    {
        Gate::authorize('update', SchoolClass::class);

        $request->validate([
            'name' => ['required', 'regex:/^[آابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی\s]+$/u', "unique:school_classes,name,$class->id"],
            'teacher_id' => 'required|exists:users,id',
            'level' => 'required|in:seven,eight,nine'
        ]);

        try {
            $class->update([
                'name' => $request->name,
                'teacher_id' => $request->teacher_id,
                'level' => $request->level,
            ]);
            return redirect()->route('classes.index')->with('success', 'ویرایش کلاس با موفقیت انجام شد');
        } catch (Throwable $e) {
            return redirect()->route('classes.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolClass $class)
    {
        Gate::authorize('delete', SchoolClass::class);

        try {
            $class->delete();
            return redirect()->route('classes.index')->with('success', 'حذف کلاس با موفقیت انجام شد');
        } catch (Throwable $e) {
            return redirect()->route('classes.index')->with('error', $e->getMessage());
        }
    }
}
