<?php

namespace App\Http\Resources\Expert;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  InvoiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'currency' => $this->currency,
            'date' => $this->date,
            'description' => $this->description,
            'is_payed' => boolval($this->is_payed),
            'total_discount' => $this->total_discount,
            'invoice_payable' => $this->invoice_payable,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,



        ];
    }
}
