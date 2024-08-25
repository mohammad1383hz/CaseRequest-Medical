<?php

namespace App\Http\Resources\Admin;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  WithdrawRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'financial_account_id'=>$this->financial_account_id,
            'price'=>$this->price,
            'description'=>$this->description,
            'date'=>$this->date,
            'status'=>$this->status,
        ];
    }
}
