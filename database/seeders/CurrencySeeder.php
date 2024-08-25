<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\FinancialAccount;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create admin
    
        $FinancialAccount= Currency::create([
            'symbol'=>'$',
            'name'=>'dollar',
        ]);
        $FinancialAccount= Currency::create([
            'symbol'=>'﷼',
            'name'=>'toman',
        ]);
       
        // User::factory()->count(50)->create();
    }
}


