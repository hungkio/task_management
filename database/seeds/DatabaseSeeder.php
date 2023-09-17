<?php

use App\Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(TaskPermissionSeeder::class);
        $this->call(CustomerSeeder::class);
    }
}
