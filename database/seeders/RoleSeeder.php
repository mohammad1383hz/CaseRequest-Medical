<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Role::create(['name' => 'admin', 'name_fa' => 'مدیر']);
        Role::create(['name' => 'supervisor', 'name_fa' => 'سرپرست',]);
        Role::create(['name' => 'expert-supervisor', 'name_fa' => 'سرپرست کارشناس']);
        Role::create(['name' => 'expert', 'name_fa' => 'کارشناس',]);
        Role::create(['name' => 'user', 'name_fa' => 'کاربر عادی']);

    }
}
