<?php

namespace App\Http\Resources\Expert;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CaseCategoryAnimalResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'case_category_id'=> $this->case_category_id,
            'title'=> $this->title,
            'description'=> $this->description,
            'price'=> $this->price,
            'commission_type'=> $this->commission_type,
            'commission_value'=> $this->commission_value,

        ];
    }
}
