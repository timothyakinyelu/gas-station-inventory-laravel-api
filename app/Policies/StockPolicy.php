<?php

namespace App\Policies;

use App\Stock;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class StockPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->permission === config('constants.SUPER-ADMIN')){
            return true;
        }
    }

    /**
     * Determine whether the user can view any stocks.
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
     * Determine whether the user can view the stock.
     *
     * @param  \App\User  $user
     * @param  \App\Stock  $stock
     * @return mixed
     */
    public function view(User $user, Stock $stock)
    {
        return $user->permission === config('constants.DATA')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this action!');
    }

    /**
     * Determine whether the user can create stocks.
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
     * Determine whether the user can update the stock.
     *
     * @param  \App\User  $user
     * @param  \App\Stock  $stock
     * @return mixed
     */
    public function update(User $user, Stock $stock)
    {
        return $user->permission === config('constants.ADMIN')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this action!');
    }

    /**
     * Determine whether the user can delete the stock.
     *
     * @param  \App\User  $user
     * @param  \App\Stock  $stock
     * @return mixed
     */
    public function delete(User $user, Stock $stock)
    {
        return $user->permission === config('constants.ADMIN')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this action!');
    }

    /**
     * Determine whether the user can restore the stock.
     *
     * @param  \App\User  $user
     * @param  \App\Stock  $stock
     * @return mixed
     */
    public function restore(User $user, Stock $stock)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the stock.
     *
     * @param  \App\User  $user
     * @param  \App\Stock  $stock
     * @return mixed
     */
    public function forceDelete(User $user, Stock $stock)
    {
        //
    }
}
