<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseCategoryExpertCommission extends Model
{
    use HasFactory;
    protected $table='case_categories_expert_commission';
    protected $guarded = [];
    public function caseCategoryExpert()
    {
        return $this->belongsTo(CaseCategoryExpert::class);
    }
}
