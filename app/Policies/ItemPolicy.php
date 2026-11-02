<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Item $item): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    public function update(User $user, Item $item): bool
    {
        return $user->role === 'ADMIN' || $user->role === 'STAFF';
    }

    public function delete(User $user, Item $item): bool
    {
        return $user->isAdministrator();
    }

    public function viewLowStockReport(User $user): bool
    {
        return $user->role === 'ADMIN' || $user->role === 'STAFF';
    }
}
