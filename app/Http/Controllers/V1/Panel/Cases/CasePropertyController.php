<?php

namespace App\Http\Controllers\V1\Panel\Cases;
use App\Http\Controllers\Controller;
use App\Http\Resources\Panel\CaseCategoryAnimalCollection;
use App\Http\Resources\Panel\CaseCategoryCollection;
use App\Http\Resources\Panel\CaseCategoryExpertCollection;
use App\Http\Resources\Panel\CaseCategoryFiledCollection;
use App\Http\Resources\Panel\CaseGroupCollection;
use App\Models\CaseCategory;
use App\Models\CaseCategoryAnimal;
use App\Models\CaseGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\CaseRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

;


class CasePropertyController extends Controller
{
    // public function __construct(Request $request)
    //     {
    //         $user = Auth::guard('sanctum')->user();
    //         if ($user->is_active === null) {
    //             abort(JsonResponse::HTTP_UNAUTHORIZED, 'not active user');
    //         }
    //     }

    public function getCaseGroupes(){
        $caseGroupes=CaseGroup::all();
        return new CaseGroupCollection($caseGroupes);
    }
    public function getCaseCategoriesCaseGroupes(CaseGroup $caseGroup){
        // dd($caseGroup);
        $caseCategories=$caseGroup->CaseCategories;
          return new CaseCategoryCollection($caseCategories);
      }

    public function getCaseCategoryAnimals(CaseCategory $caseCategory){
        $caseCategoryAnimals=$caseCategory->CaseCategoryAnimals;
        return new CaseCategoryAnimalCollection($caseCategoryAnimals);
    }
    public function getCaseCategoryExperts(CaseCategory $caseCategory){
        $caseCategoryExperts=$caseCategory->CaseCategoryExperts;
        return new CaseCategoryExpertCollection($caseCategoryExperts);
    }

    public function getCaseCategoryFileds(CaseCategoryAnimal $caseCategoryAnimal){
        $caseCategoryFiledes=$caseCategoryAnimal->CaseCategoryFileds;
        return new CaseCategoryFiledCollection($caseCategoryFiledes);
    }



    // public function getCaseCategoryExpertCommission(){
    //     $caseCategoryExpertCommissions=CaseCategoryExpertCommission::all();
    //     return new CaseCategoryExpertCommissionCollection($caseCategoryExpertCommissions);
    // }





}







