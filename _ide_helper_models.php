<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $student_id
 * @property string $date
 * @property string $status
 * @property int|null $delay
 * @property int $is_excused
 * @property string|null $description
 * @property int $registered_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $register
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereDelay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereIsExcused($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereRegisteredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUpdatedAt($value)
 */
	class Attendance extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Disciplinary> $disciplinaries
 * @property-read int|null $disciplinaries_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CaseOfDisciplinary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CaseOfDisciplinary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CaseOfDisciplinary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CaseOfDisciplinary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CaseOfDisciplinary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CaseOfDisciplinary whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CaseOfDisciplinary whereUpdatedAt($value)
 */
	class CaseOfDisciplinary extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $student_id
 * @property string $date
 * @property int $case_id
 * @property string|null $description
 * @property int $registered_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CaseOfDisciplinary $case
 * @property-read \App\Models\User $register
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary whereCaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary whereRegisteredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disciplinary withoutTrashed()
 */
	class Disciplinary extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $teacher_id
 * @property string $name
 * @property string $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \App\Models\User $teacher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolClass withoutTrashed()
 */
	class SchoolClass extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $family
 * @property int $class_id
 * @property string $national_code
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $attendances
 * @property-read int|null $attendances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentPhone> $phones
 * @property-read int|null $phones_count
 * @property-read \App\Models\SchoolClass $schoolClass
 * @method static \Database\Factories\StudentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNationalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student withoutTrashed()
 */
	class Student extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $student_id
 * @property string $phone_for
 * @property string $phone_num
 * @property int $is_virtual
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone whereIsVirtual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone wherePhoneFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone wherePhoneNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentPhone withoutTrashed()
 */
	class StudentPhone extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $student_id
 * @property string $father_name
 * @property string $previous_school
 * @property string $date_of_birth
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile wherePreviousSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentProfile withoutTrashed()
 */
	class StudentProfile extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $mobile
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\SchoolClass|null $schoolClass
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

