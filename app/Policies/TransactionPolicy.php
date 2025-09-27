<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Transaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Transaction');
    }

    public function view(AuthUser $authUser, Transaction $transaction): bool
    {
        return $authUser->can('View:Transaction');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Transaction');
    }

    public function update(AuthUser $authUser, Transaction $transaction): bool
    {
        return $authUser->can('Update:Transaction');
    }

    public function delete(AuthUser $authUser, Transaction $transaction): bool
    {
        return $authUser->can('Delete:Transaction');
    }

    public function restore(AuthUser $authUser, Transaction $transaction): bool
    {
        return $authUser->can('Restore:Transaction');
    }

    public function forceDelete(AuthUser $authUser, Transaction $transaction): bool
    {
        return $authUser->can('ForceDelete:Transaction');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Transaction');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Transaction');
    }

    public function replicate(AuthUser $authUser, Transaction $transaction): bool
    {
        return $authUser->can('Replicate:Transaction');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Transaction');
    }

}