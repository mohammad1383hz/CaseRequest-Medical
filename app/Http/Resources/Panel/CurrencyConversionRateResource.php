<?php

namespace App\Http\Resources\Panel;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CurrencyConversionRateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'rate'=> $this->rate,
            'currency_id'=> $this->currency_id,
        ];
    }
}
