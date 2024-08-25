<?php

namespace Database\Seeders;

use App\Models\FinancialAccount;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create admin
        $admin = User::create(
            [
                'first_name' => 'admin',
                'email' => 'admin@admin.com',
                'phone' => '09391234567',
                'password' => Hash::make('12345678')
            ]
        );
        $FinancialAccount= FinancialAccount::create([
            'user_id'=>$admin->id,
            'account_type'=>'user',
            'name'=>'test',
        ]);
        $FinancialAccount= FinancialAccount::create([
            'user_id'=>$admin->id,
            'account_type'=>'wallet',
            'name'=>'test',
        ]);
        $FinancialAccount= FinancialAccount::create([
            'account_type'=>'gateway',
            'name'=>'test',
        ]);
        $FinancialAccount= FinancialAccount::create([
            'account_type'=>'app',
            'name'=>'test',
        ]);
        $admin->assignRole('admin');

        // User::factory()->count(50)->create();
    }
}


