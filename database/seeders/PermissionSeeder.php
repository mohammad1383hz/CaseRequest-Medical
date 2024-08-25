<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Administrator
        $roleAdmin = Role::where('name', 'admin')->first();
        // handler
        // Create permissions
        Permission::create(['name' => 'index']);
        // Role
        Permission::create(['name' => 'role.index']);
        Permission::create(['name' => 'role.store']);
        Permission::create(['name' => 'role.show']);
        Permission::create(['name' => 'role.update']);
        Permission::create(['name' => 'role.destroy']);
        // Permission
        Permission::create(['name' => 'permission.index']);
        Permission::create(['name' => 'permission.store']);
        Permission::create(['name' => 'permission.show']);
        Permission::create(['name' => 'permission.update']);
        Permission::create(['name' => 'permission.destroy']);
         // caseCategoryAnimal
        Permission::create(['name' => 'caseCategoryAnimal.index']);
        Permission::create(['name' => 'caseCategoryAnimal.store']);
        Permission::create(['name' => 'caseCategoryAnimal.show']);
        Permission::create(['name' => 'caseCategoryAnimal.update']);
        Permission::create(['name' => 'caseCategoryAnimal.destroy']);
        // caseCategory
        Permission::create(['name' => 'caseCategory.index']);
        Permission::create(['name' => 'caseCategory.store']);
        Permission::create(['name' => 'caseCategory.show']);
        Permission::create(['name' => 'caseCategory.update']);
        Permission::create(['name' => 'caseCategory.destroy']);

        // caseGroup
        Permission::create(['name' => 'caseGroup.index']);
        Permission::create(['name' => 'caseGroup.store']);
        Permission::create(['name' => 'caseGroup.show']);
        Permission::create(['name' => 'caseGroup.update']);
        Permission::create(['name' => 'caseGroup.destroy']);

        // caseCategoryFiled
        Permission::create(['name' => 'caseCategoryFiled.index']);
        Permission::create(['name' => 'caseCategoryFiled.store']);
        Permission::create(['name' => 'caseCategoryFiled.show']);
        Permission::create(['name' => 'caseCategoryFiled.update']);
        Permission::create(['name' => 'caseCategoryFiled.destroy']);

        // caseCategoryExpert
        Permission::create(['name' => 'caseCategoryExpert.index']);
        Permission::create(['name' => 'caseCategoryExpert.store']);
        Permission::create(['name' => 'caseCategoryExpert.show']);
        Permission::create(['name' => 'caseCategoryExpert.update']);
        Permission::create(['name' => 'caseCategoryExpert.destroy']);
        //

        Permission::create(['name' => 'caseCategoryExpertCommission.index']);
        Permission::create(['name' => 'caseCategoryExpertCommission.store']);
        Permission::create(['name' => 'caseCategoryExpertCommission.show']);
        Permission::create(['name' => 'caseCategoryExpertCommission.update']);
        Permission::create(['name' => 'caseCategoryExpertCommission.destroy']);
        

        Permission::create(['name' => 'caseAssignment.index']);
        Permission::create(['name' => 'caseAssignment.store']);
        Permission::create(['name' => 'caseAssignment.show']);
        Permission::create(['name' => 'caseAssignment.update']);
        Permission::create(['name' => 'caseAssignment.destroy']);

        Permission::create(['name' => 'caseReport.index']);
        Permission::create(['name' => 'caseReport.store']);
        Permission::create(['name' => 'caseReport.show']);
        Permission::create(['name' => 'caseReport.update']);
        Permission::create(['name' => 'caseReport.destroy']);


        Permission::create(['name' => 'caseReportSurvey.index']);
        Permission::create(['name' => 'caseReportSurvey.store']);
        Permission::create(['name' => 'caseReportSurvey.show']);
        Permission::create(['name' => 'caseReportSurvey.update']);
        Permission::create(['name' => 'caseReportSurvey.destroy']);

        Permission::create(['name' => 'caseReportSurveyFiled.index']);
        Permission::create(['name' => 'caseReportSurveyFiled.store']);
        Permission::create(['name' => 'caseReportSurveyFiled.show']);
        Permission::create(['name' => 'caseReportSurveyFiled.update']);
        Permission::create(['name' => 'caseReportSurveyFiled.destroy']);
        
        Permission::create(['name' => 'caseRequest.index']);
        Permission::create(['name' => 'caseRequest.store']);
        Permission::create(['name' => 'caseRequest.show']);
        Permission::create(['name' => 'caseRequest.update']);
        Permission::create(['name' => 'caseRequest.destroy']);

        Permission::create(['name' => 'caseViolation.index']);
        Permission::create(['name' => 'caseViolation.store']);
        Permission::create(['name' => 'caseViolation.show']);
        Permission::create(['name' => 'caseViolation.update']);
        Permission::create(['name' => 'caseViolation.destroy']);

        Permission::create(['name' => 'coupon.index']);
        Permission::create(['name' => 'coupon.store']);
        Permission::create(['name' => 'coupon.show']);
        Permission::create(['name' => 'coupon.update']);
        Permission::create(['name' => 'coupon.destroy']);

        Permission::create(['name' => 'country.index']);
        Permission::create(['name' => 'country.store']);
        Permission::create(['name' => 'country.destroy']);
        

        Permission::create(['name' => 'currency.index']);
        Permission::create(['name' => 'currency.store']);
        Permission::create(['name' => 'currency.show']);
        Permission::create(['name' => 'currency.update']);
        Permission::create(['name' => 'currency.destroy']);
        
        Permission::create(['name' => 'currencyConversion.index']);
        Permission::create(['name' => 'currencyConversion.store']);
        Permission::create(['name' => 'currencyConversion.show']);
        Permission::create(['name' => 'currencyConversion.update']);
        Permission::create(['name' => 'currencyConversion.destroy']);
        
              
        Permission::create(['name' => 'financialaccount.index']);
        Permission::create(['name' => 'financialaccount.store']);
        Permission::create(['name' => 'financialaccount.show']);
        Permission::create(['name' => 'financialaccount.update']);
        Permission::create(['name' => 'financialaccount.destroy']);
        
        Permission::create(['name' => 'withdrawRequest.index']);
        Permission::create(['name' => 'withdrawRequest.store']);
        Permission::create(['name' => 'withdrawRequest.show']);
        Permission::create(['name' => 'withdrawRequest.update']);
        Permission::create(['name' => 'withdrawRequest.destroy']);

        Permission::create(['name' => 'user.index']);
        Permission::create(['name' => 'user.store']);
        Permission::create(['name' => 'user.show']);
        Permission::create(['name' => 'user.update']);
        Permission::create(['name' => 'user.destroy']);
        
        
        
        
        
        
        // Give permission to admin
        //
        $roleAdmin->givePermissionTo('index');

        $roleAdmin->givePermissionTo('role.index');
        $roleAdmin->givePermissionTo('role.store');
        $roleAdmin->givePermissionTo('role.show');
        $roleAdmin->givePermissionTo('role.update');
        $roleAdmin->givePermissionTo('role.destroy');
        // Permission To admin
        $roleAdmin->givePermissionTo('permission.index');
        $roleAdmin->givePermissionTo('permission.store');
        $roleAdmin->givePermissionTo('permission.show');
        $roleAdmin->givePermissionTo('permission.update');
        $roleAdmin->givePermissionTo('permission.destroy');

        $roleAdmin->givePermissionTo('caseCategoryAnimal.index');
        $roleAdmin->givePermissionTo('caseCategoryAnimal.store');
        $roleAdmin->givePermissionTo('caseCategoryAnimal.show');
        $roleAdmin->givePermissionTo('caseCategoryAnimal.update');
        $roleAdmin->givePermissionTo('caseCategoryAnimal.destroy');

        $roleAdmin->givePermissionTo('caseCategory.index');
        $roleAdmin->givePermissionTo('caseCategory.store');
        $roleAdmin->givePermissionTo('caseCategory.show');
        $roleAdmin->givePermissionTo('caseCategory.update');
        $roleAdmin->givePermissionTo('caseCategory.destroy');


        $roleAdmin->givePermissionTo('caseCategoryFiled.index');
        $roleAdmin->givePermissionTo('caseCategoryFiled.store');
        $roleAdmin->givePermissionTo('caseCategoryFiled.show');
        $roleAdmin->givePermissionTo('caseCategoryFiled.update');
        $roleAdmin->givePermissionTo('caseCategoryFiled.destroy');

        $roleAdmin->givePermissionTo('caseGroup.index');
        $roleAdmin->givePermissionTo('caseGroup.store');
        $roleAdmin->givePermissionTo('caseGroup.show');
        $roleAdmin->givePermissionTo('caseGroup.update');
        $roleAdmin->givePermissionTo('caseGroup.destroy');

        $roleAdmin->givePermissionTo('caseCategoryExpert.index');
        $roleAdmin->givePermissionTo('caseCategoryExpert.store');
        $roleAdmin->givePermissionTo('caseCategoryExpert.show');
        $roleAdmin->givePermissionTo('caseCategoryExpert.update');
        $roleAdmin->givePermissionTo('caseCategoryExpert.destroy');


        
       
    }
}
