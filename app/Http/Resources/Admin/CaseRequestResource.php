<?php

namespace App\Http\Resources\Admin;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use App\Models\User;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CaseRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = User::where("id", $this->user_id)->first();
        $currency=$user->currency_id;
        // if($currency != 1){
        //    $currencyConversionRate= CurrencyConversionRate::where("currency_id",$user->currency_id)->first();
        //     $rate=$currencyConversionRate->rate;    
        // }
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'case_category_animal_id' => $this->case_category_animal_id,
            'case_category_expert_id' => $this->case_category_expert_id,
            'title' => $this->title,
            'document_no' => $this->document_no,
            'owner_name' => $this->owner_name,
            'animal_name' => $this->animal_name,
            'priority' => $this->priority,
            'price' => $this->getTotalPrice(),
            'commission_price' => $this->getTotalPrice(),
            'caseRequestFields' => new CaseRequestFieldsCollection($this->caseRequestFields),            
            'files' => $this->caseFiles->map(function ($caseFile) {
                return $caseFile->file;
            }),

            'currency_id' => $currency,

            'status' => $this->status,

            
        ];
    }
}
