<?php

namespace App\Http\Resources\Panel;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'case_group_id'=> $this->case_group_id,
            'case_group'=>new CaseGroupResource ($this->group),

            'title'=> $this->title,
            'description'=> $this->description,

        ];
    }
}
