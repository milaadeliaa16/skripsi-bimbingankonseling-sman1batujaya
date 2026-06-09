<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\KonselingGuruBk;
use Illuminate\Auth\Access\HandlesAuthorization;

class KonselingGuruBkPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:KonselingGuruBk');
    }

    public function view(AuthUser $authUser, KonselingGuruBk $konselingGuruBk): bool
    {
        return $authUser->can('View:KonselingGuruBk');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:KonselingGuruBk');
    }

    public function update(AuthUser $authUser, KonselingGuruBk $konselingGuruBk): bool
    {
        return $authUser->can('Update:KonselingGuruBk');
    }

    public function delete(AuthUser $authUser, KonselingGuruBk $konselingGuruBk): bool
    {
        return $authUser->can('Delete:KonselingGuruBk');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:KonselingGuruBk');
    }

    public function restore(AuthUser $authUser, KonselingGuruBk $konselingGuruBk): bool
    {
        return $authUser->can('Restore:KonselingGuruBk');
    }

    public function forceDelete(AuthUser $authUser, KonselingGuruBk $konselingGuruBk): bool
    {
        return $authUser->can('ForceDelete:KonselingGuruBk');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:KonselingGuruBk');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:KonselingGuruBk');
    }

    public function replicate(AuthUser $authUser, KonselingGuruBk $konselingGuruBk): bool
    {
        return $authUser->can('Replicate:KonselingGuruBk');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:KonselingGuruBk');
    }

}