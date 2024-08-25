<?php

namespace App\Http\Resources\Admin;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  StatusNotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_fa' => $this->name_fa,
            'fcm'=> $this->fcm,

            'sms' => $this->sms,
            'mail'=> $this->mail,
 'title' => $this->title,
            'description'=> $this->description,
        ];
    }
}
