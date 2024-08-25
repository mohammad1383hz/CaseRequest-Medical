<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\File;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {





        

        
  
            File::create(['parent_id' => null, 'src' => '/storage','type'=>'directory']); //1
            File::create(['parent_id' => 1, 'src' => '/storage/avatar','type'=>'directory', 'name'=> 'avatar']); //2
            File::create(['parent_id' => 1, 'src' => '/storage/img_1','type'=>'directory', 'name'=> 'img_1']); //3
            File::create(['parent_id' => 1, 'src' => '/storage/img_2','type'=>'directory', 'name'=> 'img_2']);//4
            File::create(['parent_id' => 1, 'src' => '/storage/national_cart','type'=>'directory', 'name'=> 'national_cart']);//5
            File::create(['parent_id' => 1, 'src' => '/storage/case_research_file','type'=>'directory', 'name'=> 'case_research_file']); //6
            File::create(['parent_id' => 1, 'src' => '/storage/case_report_file','type'=>'directory', 'name'=> 'case_report_file']); //7
            File::create(['parent_id' => 1, 'src' => '/storage/case_request_file','type'=>'directory', 'name'=> 'case_request_file']); //8
            File::create(['parent_id' => 1, 'src' => '/storage/withdraw_request_file','type'=>'directory', 'name'=> 'case_request_file']); //9


            
            
            
      

    }
}
