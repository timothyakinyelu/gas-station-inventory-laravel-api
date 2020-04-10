<?php

namespace App\Policies;

use App\Supply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SupplyPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->permission === config('constants.SUPER-ADMIN')){
            return true;
        }
    }

    /**
     * Determine whether the user can view any supplies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->permission === config('constants.ADMIN')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this action!');
    }

    /**
     * Determine whether the user can view the supply.
     *
     * @param  \App\User  $user
     * @param  \App\Supply  $supply
     * @return mixed
     */
    public function view(User $user, Supply $supply)
    {
        return $user->permission === config('constants.DATA')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this action!');
    }

    /**
     * Determine whether the user can create supplies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permission === config('constants.DATA')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this action!');
    }

    /**
     * Determine whether the user can update the supply.
     *
     * @param  \App\User  $user
     * @param  \App\Supply  $supply
     * @return mixed
     */
    public function update(User $user, Supply $supply)
    {
        return $user->permission === config('constants.ADMIN')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this action!');
    }

    /**
     * Determine whether the user can delete the supply.
     *
     * @param  \App\User  $user
     * @param  \App\Supply  $supply
     * @return mixed
     */
    public function delete(User $user, Supply $supply)
    {
        return $user->permission === config('constants.ADMIN')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this action!');
    }

    /**
     * Determine whether the user can restore the supply.
     *
     * @param  \App\User  $user
     * @param  \App\Supply  $supply
     * @return mixed
     */
    public function restore(User $user, Supply $supply)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the supply.
     *
     * @param  \App\User  $user
     * @param  \App\Supply  $supply
     * @return mixed
     */
    public function forceDelete(User $user, Supply $supply)
    {
        //
    }
}
