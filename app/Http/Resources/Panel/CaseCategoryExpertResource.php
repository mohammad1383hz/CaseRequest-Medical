<?php

namespace App\Http\Resources\Panel;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use App\Models\CurrencyConversionRate;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class  CaseCategoryExpertResource extends JsonResource
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
            'case_category_id'=> $this->case_category_id,
            'case_category'=> new CaseCategoryResource($this->caseCategory),

            'title'=> $this->title,
            'description'=> $this->description,
            'refrence_index'=> $this->refrence_index,
            'price' =>$currency == 1 ? $this->price : $this->price*$rate,
            'commission_type'=> $this->commission_type,
            'commission_value' =>$currency == 1 || $this->commission_type == 'percent' ? $this->commission_value : $this->commission_value*$rate,
            'golden_minutes'=> $this->golden_minutes,
            'has_penalty'=> $this->has_penalty,
            'penalty_type'=> $this->penalty_type,
            'penalty_value' =>$currency == 1 || $this->commission_type == 'percent' ? $this->penalty_value : $this->penalty_value*$rate,
            'currency_id'=> $currency,
        ];
    }
}
