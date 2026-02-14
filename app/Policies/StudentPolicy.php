<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
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
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): bool
    {
        return false;
    }
    /**
     * Determine whether the user can view the model.
     */
    public function viewAllInformation(User $user): bool
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
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'admin',
            'EducationOfficer',
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
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
     * Determine whether the user can delete the model.
     */
    public function delete(): bool
    {
        return auth()->user()->hasRole([
            'super_admin',
            'admin',
        ]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Student $student): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Student $student): bool
    {
        return false;
    }
    /**
     * Determine whether the user can permanently delete the model.
     */
    public function showStudentByClass(User $user): bool
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
