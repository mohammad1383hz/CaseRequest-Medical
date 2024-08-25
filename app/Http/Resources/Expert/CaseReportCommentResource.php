<?php

namespace App\Http\Resources\Admin;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CaseReportCommentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'case_report_id' => $this->case_report_id,
            'case_report' => new CaseReportResource($this->caseReport),

            'parent_id'=> $this->parent_id,
            'message'=> $this->message,
            'status'=> $this->status,
           
        ];
    }
}
