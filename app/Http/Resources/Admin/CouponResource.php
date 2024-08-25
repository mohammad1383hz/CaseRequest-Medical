<?php

namespace App\Http\Resources\Admin;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CouponResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,



            // 'user_id'=> $this->user_id,
            'end_date'=> $this->end_date,
            'case_category_id'=> $this->case_category_id,
            'case_group_id'=> $this->case_group_id,
            'count'=> $this->count,
            'use_count'=> $this->use_count,

            'code'=> $this->code,
            'filter_user'=>boolval( $this->filter_user),

            'description'=> $this->description,
            'discount'=> $this->discount,
            'type'=> $this->type,

        ];
    }
}
