<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $users = User::with('roles')->get();
        return view('Employees.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', User::class);

        $roles = Role::all();
        return view('Employees.add', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', User::class);
        $request->validate([
            'name' => ['required', 'string', 'regex:/^[آ-یءئؤإأۀە\s]+$/u', 'unique:users,name'],
            'email' => 'required|string|unique:users,email|email',
            'mobile' => ['required', 'string', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile'],
            'username' => 'required|string|min:5|unique:users,username',
            'password' => 'required|string|min:5|confirmed',
            'roles' => ['required', 'array'],
            'roles.*' => [
                'numeric',
                Rule::exists('roles', 'id')
            ],
        ], [
            'roles.required' => 'انتخاب حداقل یکی از نقش ها الزامی است',
            'roles.*.exists' => 'نقش انتخاب‌شده معتبر نیست',
        ]);


        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                ]);
                foreach ($request->roles as $role) {
                    $user->roles()->attach($role);
                }
            });
            return redirect()->route('employees.index')->with('success', 'کاربر جدید با موفقیت افزوده شد');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('employees.index')->with('error', 'خطا. دوباره سعی کنید');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $employee)
    {
        Gate::authorize('view', User::class);

        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        Gate::authorize('update', User::class);

        $roles = Role::all();
        return view('Employees.edit', compact('employee', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $employee)
    {
        Gate::authorize('update', User::class);
        $request->validate([
            'name' => ['required', 'regex:/^[آ-یءئؤإأۀە\s]+$/u', "unique:users,name,$employee->id"],
            'email' => "required|email|unique:users,email,$employee->id",
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/', "unique:users,mobile,$employee->id"],
            'username' => "required|min:5|unique:users,username,$employee->id",
            'password' => 'nullable|min:5|confirmed',
            'roles' => ['nullable', 'array'],
            'roles.*' => [
                'numeric',
                Rule::exists('roles', 'id')
            ],
        ], [
            'roles.required' => 'انتخاب حداقل یکی از نقش ها الزامی است',
            'roles.*.exists' => 'نقش انتخاب‌شده معتبر نیست',
        ]);

        $password = $employee->password;
        if ($request->password)
            $password = Hash::make($request->password);

        try {
            DB::transaction(function () use ($employee, $request, $password) {
                $employee->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'username' => $request->username,
                    'password' => $password,
                ]);


                $roles = $request->input('roles', []);

                $employee->roles()->sync($roles);

            });
        } catch (Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('employees.index')->with('success', "تغییرات با موفقیت اعمال شد");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        Gate::authorize('delete', User::class);

        $validation = Validator::make(['id' => $employee->id], [
            'id' =>
                function ($attribute, $value, $fail) {
                    if (SchoolClass::where('teacher_id', $value)->exists())
                        $fail('این کاربر، استاد راهنمای یک کلاس است. امکان حذف وجود ندارد');
                }
        ]);
        if ($validation->fails())
            return redirect()->route('employees.index')->with('error', $validation->errors()->first());

        $employee->load('attendances');
        $count = $employee->attendances->count();
        if ($count != 0)
            return redirect()->route('employees.index')->with('error', 'این کاربر تعدادی حضورغیاب انجام داده. امکان حذف وجود ندارد');

        $employee->delete();
        return redirect()->route('employees.index')->with('success', "کاربر $employee->name با موفقیت حذف شد");
    }
}
