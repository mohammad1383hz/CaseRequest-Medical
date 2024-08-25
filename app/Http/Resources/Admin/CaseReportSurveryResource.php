<?php

namespace App\Http\Resources\Admin;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use App\Models\CaseReportSurveryField;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CaseReportSurveryResource extends JsonResource
{
    public function toArray($request): array
    {
        $case_survery_field = CaseReportSurveryField::find($this->case_survery_field_id);

        if ($case_survery_field->options) {
            $options = json_decode($case_survery_field->options, true); // Decode JSON options
        
        

            $valueString = $this->value;
            $sanitizedString = str_replace("'", '"', $valueString);
            $sanitizedString = trim($sanitizedString, " \t\n\r\0\x0B[]");
            
            $valueArray = json_decode("[$sanitizedString]", true);
            $selectedOptions = array_filter($options, function($option) use ($valueArray){
                return in_array($option['name'], $valueArray);
            });
    
        }
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'case_survery_field_id' => $this->case_survery_field_id,
            'case_report_id' => $this->case_report_id,
            'value' => $case_survery_field->options ? $selectedOptions : $this->value,

            




        ];
    }
}
