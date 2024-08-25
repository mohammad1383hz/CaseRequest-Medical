<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseCategoryExpert extends Model
{
    use HasFactory;
    protected $table='case_categories_expert';
    protected $guarded = [];
    public function caseCategory()
    {
        return $this->belongsTo(CaseCategory::class);
    }
   
}
