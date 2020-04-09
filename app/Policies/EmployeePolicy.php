<?php

namespace App\Policies;

use App\Employee;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->permission === config('constants.SUPER-ADMIN')){
            return true;
        }
    }

    /**
     * Determine whether the user can view any employees.
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
     * Determine whether the user can view the employee.
     *
     * @param  \App\User  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function view(User $user, Employee $employee)
    {
        //
    }

    public function create(User $user)
    {
        return $user->permission === config('constants.ADMIN')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this task!');
    }

    /**
     * Determine whether the user can update the employee.
     *
     * @param  \App\User  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function update(User $user, Employee $employee)
    {
        return $user->permission === config('constants.ADMIN')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this task!');
    }

    /**
     * Determine whether the user can delete the employee.
     *
     * @param  \App\User  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function delete(User $user, Employee $employee)
    {
        return $user->permission === config('constants.ADMIN')
            ? Response::allow()
            : Response::deny('You are not allowed to perform this task!');
    }

    /**
     * Determine whether the user can permanently delete the employee.
     *
     * @param  \App\User  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function forceDelete(User $user, Employee $employee)
    {
        //
    }
}
