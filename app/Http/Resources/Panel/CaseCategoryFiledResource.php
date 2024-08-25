<?php

namespace App\Http\Resources\Panel;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CaseCategoryFiledResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'case_category_animal_id'=> $this->case_category_animal_id,
            'case_category_animal'=> new CaseCategoryAnimalResource ($this->caseCategoryAnimal),

            'name'=> $this->name,
            'title'=> $this->title,
            'placeholder'=> $this->placeholder,
            'index'=> $this->index,
            'type'=> $this->type,
            'options'=> $this->options,
        ];
    }
}
