<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use App\Domain\Acl\Models\Permission;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            [
                'name' => 'customer.view',
                'en' => ['display_name' => 'Case Status'],
                'vi' => ['display_name' => 'Case Status'],
            ],

            [
                'name' => 'customers.view',
                'en' => ['display_name' => 'View Customer'],
                'vi' => ['display_name' => 'Xem khách hàng'],
            ],
            [
                'name' => 'customers.create',
                'en' => ['display_name' => 'Create Customer'],
                'vi' => ['display_name' => 'Thêm khách hàng'],
            ],
            [
                'name' => 'customers.update',
                'en' => ['display_name' => 'Update Customer'],
                'vi' => ['display_name' => 'Cập nhật khách hàng'],
            ],
            [
                'name' => 'customers.delete',
                'en' => ['display_name' => 'Delete Customer'],
                'vi' => ['display_name' => 'Xóa khách hàng'],
            ],

            [
                'name' => 'ax.view',
                'en' => ['display_name' => 'View AX'],
                'vi' => ['display_name' => 'Xem AX'],
            ],
            [
                'name' => 'ax.create',
                'en' => ['display_name' => 'Create AX'],
                'vi' => ['display_name' => 'Thêm AX'],
            ],
            [
                'name' => 'ax.update',
                'en' => ['display_name' => 'Update AX'],
                'vi' => ['display_name' => 'Cập nhật AX'],
            ],
            [
                'name' => 'ax.delete',
                'en' => ['display_name' => 'Delete AX'],
                'vi' => ['display_name' => 'Xóa AX'],
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
