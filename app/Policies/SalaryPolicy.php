<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Salary;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalaryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Salary');
    }

    public function view(AuthUser $authUser, Salary $salary): bool
    {
        return $authUser->can('View:Salary');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Salary');
    }

    public function update(AuthUser $authUser, Salary $salary): bool
    {
        return $authUser->can('Update:Salary');
    }

    public function delete(AuthUser $authUser, Salary $salary): bool
    {
        return $authUser->can('Delete:Salary');
    }

    public function restore(AuthUser $authUser, Salary $salary): bool
    {
        return $authUser->can('Restore:Salary');
    }

    public function forceDelete(AuthUser $authUser, Salary $salary): bool
    {
        return $authUser->can('ForceDelete:Salary');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Salary');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Salary');
    }

    public function replicate(AuthUser $authUser, Salary $salary): bool
    {
        return $authUser->can('Replicate:Salary');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Salary');
    }

}