<?php

namespace App\Http\Resources\Panel;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CaseResearchResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,


            'case_request_id'=>$this->case_request_id,

            'title'=> $this->title,
            'description'=> $this->description,
           
        ];
    }
}
