<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttendancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Attendance $attendance): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Attendance $attendance): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attendance $attendance): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Attendance $attendance): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Attendance $attendance): bool
    {
        return false;
    }
    /**
     * Determine whether the user can permanently Registration Of All Absence.
     */
    public function absenceRegistration(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'admin',
            'dvisor',
            'ExecutiveOfficer',
            'EducationOfficer',
        ]);
    }
    /**
     * Determine whether the user can permanently add New Day For Attendance.
     */
    public function addNewDay(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'admin',
            'dvisor',
            'ExecutiveOfficer',
            'EducationOfficer',
            'AttendanceOfficer'
        ]);
    }
    /**
     * Determine whether the user can permanently Delete Day of Attendance.
     */
    public function deleteDay(User $user)
    {
        return $user->hasRole([
            'super_admin',
            'admin',
            'dvisor',
            'ExecutiveOfficer',
            'EducationOfficer',
        ]);
    }
    /**
     * Determine whether the user can permanently Report of Attendance.
     */
    public function reportIndex(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'admin',
            'dvisor',
            'ExecutiveOfficer',
            'EducationOfficer',
        ]);
    }
    /**
     * Determine whether the user can permanently send Absence Report in EitaaCHANNEL.
     */
    public function sendAbsenceReport(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'admin',
            'dvisor',
            'ExecutiveOfficer',
            'EducationOfficer',
        ]);
    }
    /**
     * Determine whether the user can permanently edit Attendance Status.
     */
    public function editAttendance(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'admin',
            'dvisor',
            'ExecutiveOfficer',
            'EducationOfficer',
        ]);
    }

    public function attendancePreviousDay(User $user)
    {
        return $user->hasRole([
            'super_admin',
            'admin',
            'dvisor',
            'ExecutiveOfficer',
            'EducationOfficer',
        ]);
    }
}
