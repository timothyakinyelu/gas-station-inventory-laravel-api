<?php

namespace App\Policies;

use App\Expense;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->permission === config('constants.SUPER-ADMIN')){
            return true;
        }
    }

    /**
     * Determine whether the user can view any expenses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->permission === config('constants.ADMIN')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this task!');
    }

    /**
     * Determine whether the user can view the expense.
     *
     * @param  \App\User  $user
     * @param  \App\Expense  $expense
     * @return mixed
     */
    public function view(User $user, Expense $expense)
    {
        return $user->permission === config('constants.DATA')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this action!');
    }

    /**
     * Determine whether the user can create expenses.
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
     * Determine whether the user can update the expense.
     *
     * @param  \App\User  $user
     * @param  \App\Expense  $expense
     * @return mixed
     */
    public function update(User $user, Expense $expense)
    {
        return $user->permission === config('constants.ADMIN')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this task!');
    }

    /**
     * Determine whether the user can delete the expense.
     *
     * @param  \App\User  $user
     * @param  \App\Expense  $expense
     * @return mixed
     */
    public function delete(User $user, Expense $expense)
    {
        return $user->permission === config('constants.ADMIN')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this task!');
    }

    /**
     * Determine whether the user can restore the expense.
     *
     * @param  \App\User  $user
     * @param  \App\Expense  $expense
     * @return mixed
     */
    public function restore(User $user, Expense $expense)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the expense.
     *
     * @param  \App\User  $user
     * @param  \App\Expense  $expense
     * @return mixed
     */
    public function forceDelete(User $user, Expense $expense)
    {
        //
    }
}
