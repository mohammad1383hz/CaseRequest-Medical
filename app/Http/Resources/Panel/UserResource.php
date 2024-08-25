<?php

namespace App\Http\Resources\Panel;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'first_name'=> $this->first_name,
            'last_name'=> $this->last_name,
            'email'=> $this->email,
            'phone'=> $this->phone,
            // 'password'=> $this->password,
            'is_email_verified' => $this->is_email_verified ?? false,
            'is_phone_verified'=> $this->is_phone_verified ?? false,

            'is_active' => $this->is_active ?? false,
            'is_blocked'=> $this->is_blocked ?? false,

            

            'avatar_file_id'=> $this->avatar_file_id ?? false,

            



            'national_cart'=> $this->national_cart_id,
            'language'=> $this->language,

            


            

            'case_score'=>$this->caseAssignments->flatMap(function ($assignment) {
                return $assignment->caseReport->pluck('case_score');
            })->avg(),
          
            'city'=> $this->city,
            'currency_id'=> $this->currency_id,
            'country_id'=> $this->country_id,
            'img_1'=> $this->img_1 ?? null,
            'img_2'=> $this->img_2 ?? null,

            
            
            'permissions' => $this->getPermissionNames(),
               'roles' => $this->getRoleNames(),




        ];
    }
}
