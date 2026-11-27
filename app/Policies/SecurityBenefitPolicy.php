<?php

namespace App\Policies;

use App\Models\User;

class SecurityBenefitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->exists;
    }
}
