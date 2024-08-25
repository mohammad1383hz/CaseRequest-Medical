<?php

namespace App\Http\Resources\Panel;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CaseReportResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'case_assignment_id'=> $this->case_assignment_id,
            'case_assignment'=> new CaseAssignmentResource($this->assignment),

            'case_score'=> $this->case_score,
            'report_score'=> $this->report_score,
            'tech'=> $this->tech,
            'interpretation'=> $this->interpretation,
            'diagnosis'=> $this->diagnosis,
            'comment'=> $this->comment,
            'files' => $this->caseFiles->map(function ($caseFile) {
                return $caseFile->file;
            }),







        ];
    }
}
