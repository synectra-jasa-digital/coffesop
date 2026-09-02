<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
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
     * Anyone with an account can read the product catalog (needed by POS).
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
    public function view(User $user, Product $product): bool
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
     * Only Owner/Admin may create new products.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Owner/Admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * Only Owner/Admin may update products.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->hasRole('Owner/Admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Only Owner/Admin may delete products.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->hasRole('Owner/Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        return $user->hasRole('Owner/Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->hasRole('Owner/Admin');
    }
}