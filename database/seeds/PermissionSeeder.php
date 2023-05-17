<?php

use App\Domain\Acl\Models\Permission;
use App\Domain\Acl\Models\Role;
use App\Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
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
               'name' => 'admins.view',
               'en' => ['display_name' => 'View Admin'],
               'vi' => ['display_name' => 'Xem quản trị viên'],
           ],
            [
                'name' => 'admins.create',
                'en' => ['display_name' => 'Create Admin'],
                'vi' => ['display_name' => 'Thêm quản trị viên'],
            ],
            [
                'name' => 'admins.update',
                'en' => ['display_name' => 'Update Admin'],
                'vi' => ['display_name' => 'Cập nhật quản trị viên'],
            ],
            [
                'name' => 'admins.delete',
                'en' => ['display_name' => 'Delete Admin'],
                'vi' => ['display_name' => 'Xóa quản trị viên'],
            ],

            [
                'name' => 'roles.view',
                'en' => ['display_name' => 'View Role'],
                'vi' => ['display_name' => 'Xem vai trò'],
            ],
            [
                'name' => 'roles.create',
                'en' => ['display_name' => 'Create Role'],
                'vi' => ['display_name' => 'Thêm vai trò'],
            ],
            [
                'name' => 'roles.update',
                'en' => ['display_name' => 'Update Role'],
                'vi' => ['display_name' => 'Cập nhật vai trò'],
            ],
            [
                'name' => 'roles.delete',
                'en' => ['display_name' => 'Delete Role'],
                'vi' => ['display_name' => 'Xóa vai trò'],
            ],

            [
                'name' => 'tasks.view',
                'en' => ['display_name' => 'View Task'],
                'vi' => ['display_name' => 'Xem case'],
            ],
            [
                'name' => 'tasks.create',
                'en' => ['display_name' => 'Create Task'],
                'vi' => ['display_name' => 'Thêm case'],
            ],
            [
                'name' => 'tasks.update',
                'en' => ['display_name' => 'Update Task'],
                'vi' => ['display_name' => 'Cập nhật case'],
            ],
            [
                'name' => 'tasks.delete',
                'en' => ['display_name' => 'Delete Task'],
                'vi' => ['display_name' => 'Xóa case'],
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $roles = [
            [
                'name' => 'admin',
                'en' => ['display_name' => 'Manager'],
                'vi' => ['display_name' => 'Quản trị viên'],
            ],
            [
                'name' => 'editor',
                'en' => ['display_name' => 'Editor'],
                'vi' => ['display_name' => 'Editor'],
            ],
            [
                'name' => 'QA',
                'en' => ['display_name' => 'QA'],
                'vi' => ['display_name' => 'QA'],
            ],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }

        $superAdminRole = Role::create([
            'name' => 'superadmin',
            'en' => ['display_name' => 'Admin'],
            'vi' => ['display_name' => 'Admin'],
        ]);

        $superAdmin = factory(Admin::class)->create([
            'email' => 'admin@gmail.com',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'password' => bcrypt('12312312'),
        ]);

        $superAdmin->assignRole($superAdminRole);
    }
}
