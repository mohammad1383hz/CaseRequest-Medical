<?php

namespace App\Http\Resources\Admin;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use App\Models\CaseCategoryField;
use App\Models\CaseReportSurveryField;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  CaseRequestFieldsResource extends JsonResource
{
    public function toArray($request): array
    {
        $case_category_field = CaseCategoryField::find($this->case_category_field_id);

        if ($case_category_field->options) {
            $options = json_decode($case_category_field->options, true); // Decode JSON options
        
        

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
            'case_category_field_id' => $this->case_category_field_id,
            'case_request_id' => $this->case_request_id,
            'value' => $case_category_field->options ? $selectedOptions : $this->value,
        ];
    }
}
