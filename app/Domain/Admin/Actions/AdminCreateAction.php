<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions;

use App\Domain\Admin\DTO\AdminData;
use App\Domain\Admin\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminCreateAction
{
    public function execute(AdminData $adminData): Admin
    {
        $admin = new Admin;

        DB::transaction(function () use ($adminData, $admin) {
            $admin->first_name = $adminData->first_name;
            $admin->last_name = $adminData->last_name;
            $admin->email = $adminData->email;
            $admin->password = Hash::make($adminData->password);
            $admin->level = $adminData->level;
            $admin->customer_for_work = $adminData->customer_for_work;
            $admin->is_ctv = $adminData->is_ctv;
            $admin->lock_task = $adminData->lock_task;
            $admin->customer = $adminData->customer;
            $admin->save();

            $admin->assignRole($adminData->roles);
        });

        return $admin;
    }
}
