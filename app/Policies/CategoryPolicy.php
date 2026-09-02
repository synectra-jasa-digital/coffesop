<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Perform pre-authorization checks.
     *
     * Owner/Admin can perform any action regardless of the policy method.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Owner/Admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     *
     * Anyone with an account can read the category list (needed by POS).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole([
            'Owner/Admin',
            'Manager/Supervisor',
            'Kasir',
            'Barista/Gudang',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): bool
    {
        return $user->hasRole([
            'Owner/Admin',
            'Manager/Supervisor',
            'Kasir',
            'Barista/Gudang',
        ]);
    }

    /**
     * Determine whether the user can create models.
     *
     * Only Owner/Admin may create new categories.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Owner/Admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * Only Owner/Admin may update categories.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->hasRole('Owner/Admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Only Owner/Admin may delete categories.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->hasRole('Owner/Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        return $user->hasRole('Owner/Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return $user->hasRole('Owner/Admin');
    }
}