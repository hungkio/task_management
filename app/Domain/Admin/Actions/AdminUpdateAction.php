<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions;

use App\Domain\Admin\DTO\AdminData;
use App\Domain\Admin\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUpdateAction
{
    public function execute(Admin $admin, AdminData $adminData): void
    {
        DB::transaction(function () use ($admin, $adminData) {
            $admin->first_name = $adminData->first_name;
            $admin->last_name = $adminData->last_name;
            $admin->email = $adminData->email;
            $admin->level = $adminData->level;
            $admin->is_ctv = $adminData->is_ctv;
            $admin->lock_task = $adminData->lock_task;
            if (! empty($adminData->password)) {
                $admin->password = Hash::make($adminData->password);
            }
            $admin->customer = $adminData->customer;
            $admin->save();
            $admin->syncRoles($adminData->roles);
        });
    }
}
