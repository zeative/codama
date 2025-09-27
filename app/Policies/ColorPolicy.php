<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Color;
use Illuminate\Auth\Access\HandlesAuthorization;

class ColorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Color');
    }

    public function view(AuthUser $authUser, Color $color): bool
    {
        return $authUser->can('View:Color');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Color');
    }

    public function update(AuthUser $authUser, Color $color): bool
    {
        return $authUser->can('Update:Color');
    }

    public function delete(AuthUser $authUser, Color $color): bool
    {
        return $authUser->can('Delete:Color');
    }

    public function restore(AuthUser $authUser, Color $color): bool
    {
        return $authUser->can('Restore:Color');
    }

    public function forceDelete(AuthUser $authUser, Color $color): bool
    {
        return $authUser->can('ForceDelete:Color');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Color');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Color');
    }

    public function replicate(AuthUser $authUser, Color $color): bool
    {
        return $authUser->can('Replicate:Color');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Color');
    }

}