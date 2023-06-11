<?php

use Illuminate\Database\Seeder;
use App\Domain\Acl\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class TaskPermissionSeeder extends Seeder
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
                'name' => 'dashboards.view',
                'en' => ['display_name' => 'Dashboard'],
                'vi' => ['display_name' => 'Dashboard'],
            ],
            [
                'name' => 'dbchecks.view',
                'en' => ['display_name' => 'Double check'],
                'vi' => ['display_name' => 'Double check'],
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
