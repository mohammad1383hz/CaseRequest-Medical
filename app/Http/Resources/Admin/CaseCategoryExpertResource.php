<?php

namespace App\Http\Resources\Admin;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CaseCategoryExpertResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'case_category_id'=> $this->case_category_id,
            'title'=> $this->title,
            'description'=> $this->description,
            'refrence_index'=> $this->refrence_index,
            'price'=> $this->price,
            'commission_type'=> $this->commission_type,
            'commission_value'=> $this->commission_value,
            'golden_minutes'=> $this->golden_minutes,
            'has_penalty'=> $this->has_penalty,
            'penalty_type'=> $this->penalty_type,
            'penalty_value'=> $this->penalty_value,
            'penalty_time'=> $this->penalty_time,
        ];
    }
}
