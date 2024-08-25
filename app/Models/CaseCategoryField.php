<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseCategoryField extends Model
{
    use HasFactory;

    protected $table='case_category_fields';
    protected $guarded = [];
    public function CaseCategoryAnimal()
    {
        return $this->belongsTo(CaseCategoryAnimal::class);
    }
}
