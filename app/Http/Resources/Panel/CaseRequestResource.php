<?php

namespace App\Http\Resources\Panel;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use App\Models\CurrencyConversionRate;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class  CaseRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = Auth::user();
        $currency=$user->currency_id;
        if($currency != 1){
           $currencyConversionRate= CurrencyConversionRate::where("currency_id",$user->currency_id)->first();
            $rate=$currencyConversionRate->rate;    
        }
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'case_category_animal_id' => $this->case_category_animal_id,
            'case_category_expert_id' => $this->case_category_expert_id,
            'case_category_animal' =>new CaseCategoryAnimalResource($this->caseCategoryAnimal),
            "case_category_expert"=>new CaseCategoryExpertResource($this->caseCategoryExpert),
            'title' => $this->title,
            'document_no' => $this->document_no,
            'owner_name' => $this->owner_name,
            'animal_name' => $this->animal_name,
            'priority' => $this->priority,
            'price' =>$currency == 1 ? $this->getTotalPrice() : $this->getTotalPrice()*$rate,
            'assignments' => $this->assignments,
            'caseRequestFields' => new CaseRequestFieldsCollection($this->caseRequestFields),            
            'currency_id'=> $currency,
            'files' => $this->caseFiles->map(function ($caseFile) {
                return $caseFile->file;
            }),
            'status' => $this->status,
        ];
    }
}
