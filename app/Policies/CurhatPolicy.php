<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Curhat;
use Illuminate\Auth\Access\HandlesAuthorization;

class CurhatPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Curhat');
    }

    public function view(AuthUser $authUser, Curhat $curhat): bool
    {
        return $authUser->can('View:Curhat');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Curhat');
    }

    public function update(AuthUser $authUser, Curhat $curhat): bool
    {
        return $authUser->can('Update:Curhat');
    }

    public function delete(AuthUser $authUser, Curhat $curhat): bool
    {
        return $authUser->can('Delete:Curhat');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Curhat');
    }

    public function restore(AuthUser $authUser, Curhat $curhat): bool
    {
        return $authUser->can('Restore:Curhat');
    }

    public function forceDelete(AuthUser $authUser, Curhat $curhat): bool
    {
        return $authUser->can('ForceDelete:Curhat');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Curhat');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Curhat');
    }

    public function replicate(AuthUser $authUser, Curhat $curhat): bool
    {
        return $authUser->can('Replicate:Curhat');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Curhat');
    }

}