<?php

namespace App\Http\Controllers;

use App\Models\ViolationTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ViolationTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', ViolationTitle::class);

        $cases = ViolationTitle::withCount('studentViolations')->get();
        return view('Violations.management.index', compact('cases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', ViolationTitle::class);

        return view('Violations.management.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', ViolationTitle::class);

        $request->validate(
            [
                'title' => ['required', 'regex:/^[آ-یءئؤإأۀە\s]+$/u', 'unique:violation_titles,title']
            ],
            [
                'title.regex' => 'تنها استفاده از حروف فارسی مجاز است.',
                'title.unique' => 'این عنوان قبلا اضافه شده است.'
            ]
        );
        ViolationTitle::create([
            'title' => $request->title
        ]);
        return redirect()->route('violation-titles.index')->with('success', 'عنوان جدید با موفقیت اضافه شد');
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
    public function edit(ViolationTitle $violationTitle)
    {
        Gate::authorize('update', ViolationTitle::class);

        return view('Violations.management.edit', compact('violationTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ViolationTitle $violationTitle)
    {
        Gate::authorize('update', ViolationTitle::class);

        $request->validate(
            [
                'title' => ['required', 'regex:/^[آ-یءئؤإأۀە\s]+$/u', "unique:violation_titles,title,{$request->id},id"]
            ],
            [
                'title.regex' => 'فقط حروف فارسی مجاز است.'
            ]
        );
        $violationTitle->update(['title' => $request->title]);
        return redirect()->route('violation-titles.index')->with('success', "عنوان با موفقیت تغییر کرد");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ViolationTitle $violationTitle)
    {
        Gate::authorize('delete', ViolationTitle::class);

        $violationTitle->load('studentViolations');
        $count = $violationTitle->studentViolations->count();
        if ($count != 0)
            return redirect()->route('violation-titles.index')->with('warning', 'ابتدا موارد ثبت شده را حذف کنید');

        $violationTitle->delete();
        return redirect()->route('violation-titles.index')->with('success', "عنوان با موفقیت حذف شد");
    }
}
