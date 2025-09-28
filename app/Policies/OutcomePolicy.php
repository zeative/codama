<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Outcome;
use Illuminate\Auth\Access\HandlesAuthorization;

class OutcomePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Outcome');
    }

    public function view(AuthUser $authUser, Outcome $outcome): bool
    {
        return $authUser->can('View:Outcome');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Outcome');
    }

    public function update(AuthUser $authUser, Outcome $outcome): bool
    {
        return $authUser->can('Update:Outcome');
    }

    public function delete(AuthUser $authUser, Outcome $outcome): bool
    {
        return $authUser->can('Delete:Outcome');
    }

    public function restore(AuthUser $authUser, Outcome $outcome): bool
    {
        return $authUser->can('Restore:Outcome');
    }

    public function forceDelete(AuthUser $authUser, Outcome $outcome): bool
    {
        return $authUser->can('ForceDelete:Outcome');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Outcome');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Outcome');
    }

    public function replicate(AuthUser $authUser, Outcome $outcome): bool
    {
        return $authUser->can('Replicate:Outcome');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Outcome');
    }

}