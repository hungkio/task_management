<?php

use Illuminate\Database\Seeder;
use App\Domain\Acl\Models\Permission;

class TaskPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'dashboards.view',
                'en' => ['display_name' => 'Dashboard'],
                'vi' => ['display_name' => 'Dashboard'],
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
        //
    }
}
