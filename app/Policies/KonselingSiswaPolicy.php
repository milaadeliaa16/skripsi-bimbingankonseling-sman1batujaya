<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\KonselingSiswa;
use Illuminate\Auth\Access\HandlesAuthorization;

class KonselingSiswaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:KonselingSiswa');
    }

    public function view(AuthUser $authUser, KonselingSiswa $konselingSiswa): bool
    {
        return $authUser->can('View:KonselingSiswa');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:KonselingSiswa');
    }

    public function update(AuthUser $authUser, KonselingSiswa $konselingSiswa): bool
    {
        return $authUser->can('Update:KonselingSiswa');
    }

    public function delete(AuthUser $authUser, KonselingSiswa $konselingSiswa): bool
    {
        return $authUser->can('Delete:KonselingSiswa');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:KonselingSiswa');
    }

    public function restore(AuthUser $authUser, KonselingSiswa $konselingSiswa): bool
    {
        return $authUser->can('Restore:KonselingSiswa');
    }

    public function forceDelete(AuthUser $authUser, KonselingSiswa $konselingSiswa): bool
    {
        return $authUser->can('ForceDelete:KonselingSiswa');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:KonselingSiswa');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:KonselingSiswa');
    }

    public function replicate(AuthUser $authUser, KonselingSiswa $konselingSiswa): bool
    {
        return $authUser->can('Replicate:KonselingSiswa');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:KonselingSiswa');
    }

}