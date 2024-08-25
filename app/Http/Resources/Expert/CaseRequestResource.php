<?php

namespace App\Http\Resources\Expert;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use App\Models\CaseAssignment;
use App\Models\CurrencyConversionRate;
use App\Models\FinancialDocument;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
class CaseRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = Auth::user();
        $currency=$user->currency_id;
        if($currency != 1){
           $currencyConversionRate= CurrencyConversionRate::where("currency_id",$user->currency_id)->first();
            $rate=$currencyConversionRate->rate;    
        }
        $case_assignment=CaseAssignment::where("case_request_id", $this->id)->where("user_id", $user->id)->first();
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'case_category_animal' => new CaseCategoryAnimalCollection($this->caseCategoryAnimal) ,
            'case_category_expert' => new CaseCategoryExpertCollection($this->caseCategoryExpert) ,
            'title' => $this->title,
            'document_no' => $this->document_no,
            'owner_name' => $this->owner_name,
            'animal_name' => $this->animal_name,
            'priority' => $this->priority,
            'price' =>$currency == 1 ? $this->getTotalPrice() : $this->getTotalPrice()*$rate,
            // 'currency' =>$currency ,
            'currency_id'=> $currency,
            'files' => $this->caseFiles->map(function ($caseFile) {
                return $caseFile->file;
            }),

            'caseRequestFields' => new CaseRequestFieldsCollection($this->caseRequestFields),            

            'status' => $this->status,
        ];
        $data['commission_price'] = $currency == 1 ? $this->getCommissionPrice() : $this->getCommissionPrice()*$rate;

        // if ($case_assignment) {
        //     $FinancialDocument=FinancialDocument::where('user_id',$user->id)->where('case_request_id',$this->id)->first();

        //     $FinancialDocument=FinancialDocument::where('user_id',$user->id)->where('case_request_id',$this->id)->first();
        //     $data['commission_price'] = $FinancialDocument->price;
        // }else{
        //     $data['commission_price'] = $currency == 1 ? $this->getCommissionPrice() : $this->getCommissionPrice()*$rate;

        // }

        return $data;
    }
}
