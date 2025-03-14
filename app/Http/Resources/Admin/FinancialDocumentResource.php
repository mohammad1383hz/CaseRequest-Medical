<?php

namespace App\Http\Resources\Admin;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  FinancialDocumentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'creditor_id' =>$this->creditor_id,
            'creditor' =>$this->creditorAccount,


            
            'debtor_id' =>$this->debtor_id,
            'debtor' =>$this->creditorAccount,

            'description' =>$this->description,
            'tracking_code' =>$this->tracking_code,
            'date' =>$this->date,
            'price' =>$this->price,
            'is_canceled' =>$this->is_canceled,
            'invoice_id' =>$this->invoice_id,
            'file_id' =>$this->file_id,
            'withdraw_request_id' =>$this->withdraw_request_id,
        ];
    }
}
