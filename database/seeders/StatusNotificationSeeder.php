<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\FinancialAccount;
use App\Models\StatusNotification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class StatusNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create admin
    
        $StatusNotification= StatusNotification::create([
            'name_fa'=>'CaseReport',
            'name'=>'CaseReport',
            'title'=>'test',
            'description'=>'test',
            'sms'=>false,
            'email'=>false,
            'fcm'=>false,
            'app'=>true,
        ]);
        $StatusNotification= StatusNotification::create([
            'name_fa'=>'EediRequiredCaseRequest',
            'name'=>'EediRequiredCaseRequest',
            'title'=>'test',
            'description'=>'test',
            'sms'=>false,
            'email'=>false,
            'fcm'=>false,
            'app'=>true,
        ]);
        $StatusNotification= StatusNotification::create([
            'name_fa'=>'SendCaseRequestForExpert',
            'name'=>'SendCaseRequestForExpert',
            'title'=>'test',
            'description'=>'test',
            'sms'=>false,
            'email'=>false,
            'fcm'=>false,
            'app'=>true,
        ]);
       
       
        // User::factory()->count(50)->create();
    }
}


