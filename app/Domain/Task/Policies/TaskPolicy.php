<?php

namespace App\Domain\Task\Policies;

use App\Domain\Admin\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Tasks;
use App\Domain\Acl\Models\Role;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @return mixed
     */
    public function viewAny(Admin $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @return mixed
     */
    public function view(Admin $user)
    {
        return $user->hasPermissionTo('tasks.view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return $user->hasPermissionTo('tasks.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Domain\Admin\Models\Admin $user
     * @param Role $role
     * @return mixed
     */
    public function update(Admin $user, Tasks $task)
    {
        return $user->hasPermissionTo('tasks.update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Domain\Admin\Models\Admin $user
     * @param Role $role
     * @return mixed
     */
    public function delete(Admin $user, Tasks $task)
    {
        return $user->hasPermissionTo('tasks.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function restore(Admin $user, Role $role)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Domain\Admin\Models\Admin  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function forceDelete(Admin $user, Role $role)
    {
        //
    }
}
