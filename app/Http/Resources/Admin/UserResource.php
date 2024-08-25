<?php

namespace App\Http\Resources\Admin;

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

            



            'src_national_cart'=> $this->src_national_cart,
            'src_img_1'=> $this->src_img_1,
            'src_img_2'=> $this->src_img_2,
            'language'=> $this->language,

            


            

            'case_score'=>$this->caseAssignments->flatMap(function ($assignment) {
                return $assignment->caseReport->pluck('case_score');
            })->avg(),
            'report_score'=>$this->caseAssignments->flatMap(function ($assignment) {
                return $assignment->caseReport->pluck('report_score');
            })->avg(),
            'time_response_score'=>$this->caseAssignments->flatMap(function ($assignment) {
                return $assignment->caseReport->pluck('time_response_score');
            })->avg(),
            'city'=> $this->city,
            'group_id'=> $this->group_id,
            'currency_id'=> $this->currency_id,
            'country_id'=> $this->country_id,

            'permissions' => $this->getPermissionNames(),
               'roles' => $this->getRoleNames(),




        ];
    }
}
