<?php

return [

    'sidebar' => [

        [
            'title' => 'داشبورد',
            'icon' => 'bi-speedometer2',
            'route' => 'dashboard',
            'url' => 'dashboard',
            'can' => null,
        ],

        [
            'title' => 'دانش آموزان',
            'icon' => 'bi-person-vcard',
            'route' => 'students.*',
            'url' => 'students.index',
            'can' => ['viewAny', \App\Models\Student::class],
        ],

        [
            'title' => 'حضور و غیاب',
            'icon' => 'bi-calendar-check',
            'route' => 'attendances.*',
            'url' => 'attendances.index',
            'can' => null,
        ],

        [
            'title' => 'تخلفات',
            'icon' => 'bi-exclamation-circle',
            'route' => 'student-violations.*',
            'url' => 'student-violations.index',
            'can' => ['viewAny', \App\Models\StudentViolation::class],
        ],

        [
            'title' => 'کلاس ها',
            'icon' => 'bi-building',
            'route' => 'classes.*',
            'url' => 'classes.index',
            'can' => ['viewAny', \App\Models\SchoolClass::class],
        ],

        [
            'title' => 'عناوین انضباطی',
            'icon' => 'bi-sign-stop',
            'route' => 'violation-titles.*',
            'url' => 'violation-titles.index',
            'can' => ['viewAny', \App\Models\ViolationTitle::class],
        ],

        [
            'title' => 'مدیریت کارکنان',
            'icon' => 'bi-people',
            'route' => 'employees.*',
            'url' => 'employees.index',
            'can' => ['viewAny', \App\Models\User::class],
        ],

    ],

];
