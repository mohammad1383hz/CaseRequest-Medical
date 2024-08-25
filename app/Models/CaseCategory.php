<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseCategory extends Model
{
    use HasFactory;
    protected $table='case_categories';
    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(CaseGroup::class);
    }
    public function caseRequests()
    {
        return $this->belongsToMany(CaseRequest::class, 'case_request_category', 'case_category_id','case_request_id');
    }
    public function CaseCategoryAnimals()
    {
        return $this->hasMany(CaseCategoryAnimal::class);
    }
    public function CaseCategoryExperts()
    {
        return $this->hasMany(CaseCategoryExpert::class);
    }
}
