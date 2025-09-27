<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Design;
use Illuminate\Auth\Access\HandlesAuthorization;

class DesignPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Design');
    }

    public function view(AuthUser $authUser, Design $design): bool
    {
        return $authUser->can('View:Design');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Design');
    }

    public function update(AuthUser $authUser, Design $design): bool
    {
        return $authUser->can('Update:Design');
    }

    public function delete(AuthUser $authUser, Design $design): bool
    {
        return $authUser->can('Delete:Design');
    }

    public function restore(AuthUser $authUser, Design $design): bool
    {
        return $authUser->can('Restore:Design');
    }

    public function forceDelete(AuthUser $authUser, Design $design): bool
    {
        return $authUser->can('ForceDelete:Design');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Design');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Design');
    }

    public function replicate(AuthUser $authUser, Design $design): bool
    {
        return $authUser->can('Replicate:Design');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Design');
    }

}