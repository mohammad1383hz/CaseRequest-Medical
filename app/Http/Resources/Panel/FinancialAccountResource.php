<?php

namespace App\Http\Resources\Panel;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  FinancialAccountResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id'=> $this->user_id,
            'payment_geteway_id'=> $this->payment_geteway_id,
            'type'=> $this->type,
            'name'=> $this->name,
            'description'=> $this->description,
            'card_number'=> $this->card_number,
            'ibank'=> $this->ibank,
            'bank'=> $this->bank,
            'account_number'=> $this->account_number,


        ];
    }
}
