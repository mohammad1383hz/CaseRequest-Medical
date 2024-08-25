<?php

namespace App\Http\Resources\Admin;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CaseCategoryExpertCommissionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            "case_category_expert_id"=>$this->case_category_expert_id,
            "time_start"=>$this->time_start,
            'time_end'=>$this->time_end,
            'commission_value'=>$this->commission_value,
            'commission_type'=>$this->commission_type,
        ];
    }
}
