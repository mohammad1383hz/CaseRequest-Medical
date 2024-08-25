<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseCategoryAnimal extends Model
{
    use HasFactory;
    protected $table='case_category_animals';
    protected $guarded = [];
    public function CaseCategoryFileds()
    {
        return $this->hasMany(CaseCategoryField::class);
    }
    public function caseCategory()
    {
        return $this->belongsTo(CaseCategory::class);
    }

}
