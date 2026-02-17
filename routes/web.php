<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentViolationController;
use App\Http\Controllers\ViolationTitleController;
use Illuminate\Support\Facades\Route;



Route::middleware('throttle:10,1')->prefix('auth')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'loginPost'])->name('login.post');
    Route::view('/forget-password', 'Auth.forget-password')->name('forgetPassword');
    Route::post('/forget-password', [AuthController::class, 'forgetPassword'])->name('forgetPassword.post');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('resetPassword');
    Route::post('/reset-password', [AuthController::class, 'resetPasswordPost'])->name('resetPasswordPost');
});



Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('change-password')->group(function () {
        Route::view('/', 'Auth.change-password')->name('changePassword');
        Route::post('/', [AuthController::class, 'changePassword'])->name('changePasswordPost');
    });

    Route::middleware('force.password.change')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('Attendances')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('attendances.index');

            Route::get('/add-new-day/{day}', [AttendanceController::class, 'addNewDay'])->name('attendances.addNewDay');
            Route::delete('/delete-day/{day}', [AttendanceController::class, 'deleteDay'])->name('attendances.deleteDay');

            Route::get('/registrationByNationalCode/{day}', [AttendanceController::class, 'registrationByNationalCode'])->name('attendances.registration.ByNationalCode');
            Route::post('/search-student-ByNationalCode', [AttendanceController::class, 'searchStudentByNationalCode'])->name('search.student.ByNationalCode');
            Route::get('/registrationByStudentCode/{day}', [AttendanceController::class, 'registrationByStudentCode'])->name('attendances.registration.ByStudentCode');
            Route::post('/search-student-ByStudentCode', [AttendanceController::class, 'searchStudentByStudentCode'])->name('search.student.ByStudentCode');
            Route::post('/confirm-student', [AttendanceController::class, 'confirmStudent'])->name('confirm.student');

            Route::get('/unknown-students/{date}', [AttendanceController::class, 'unknownStudents'])->name('unknownStudents');
            Route::get('/absence-registration/{date}', [AttendanceController::class, 'absenceRegistration'])->name('absenceRegistration');

            Route::get('/edit-attendance/{date}/{student_id}', [AttendanceController::class, 'editAttendance'])->name('editAttendance');
            Route::post('/edit-attendance', [AttendanceController::class, 'editAttendancePost'])->name('editAttendance.post');

            Route::prefix('Report')->group(function () {
                Route::get('/', [AttendanceController::class, 'reportIndex'])->name('attendance.report.index');
                Route::get('/sendAbsenceReport/{date}', [AttendanceController::class, 'sendAbsenceReport'])->name('sendAbsenceReport');
            });
        });

        Route::get('/student-violations/report', [StudentViolationController::class, 'report'])->name('student-violations.report');
        Route::resource('student-violations', StudentViolationController::class);

        Route::prefix('management')->group(function () {
            Route::resource('violation-titles', ViolationTitleController::class);
            Route::resource('employees', EmployeeController::class);
            Route::resource('classes', SchoolClassController::class);
            Route::get('/students-show/{class_id}', [StudentController::class, 'showStudentByClass'])->name('students.showStudentByClass');
            Route::get('/pending-assignment/', [StudentController::class, 'pendingAssignment'])->name('students.pendingAssignment');

            Route::view('/students/import-students', 'Students.importStudents')->name('students.createBatch');
            Route::get('/students/import-students/download-template', [StudentController::class, 'students.downloadExcelTemplate'])->name('students.downloadImportTemplate');
            Route::post('/students/import-students', [StudentController::class, 'importStudents'])->name('students.importStudents.store');
            Route::resource('students', StudentController::class);
        });
    });

});
