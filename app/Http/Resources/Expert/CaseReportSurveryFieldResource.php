<?php

namespace App\Http\Resources\Expert;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CaseReportSurveryFieldResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name'=> $this->name,
            'title'=> $this->title,
            'placeholder'=> $this->placeholder,
            'type'=> $this->type,
            'number'=> $this->number,
            'options'=> $this->options,



        ];
    }
}
