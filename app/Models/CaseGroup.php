<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseGroup extends Model
{
    use HasFactory;
    protected $table='case_groups';
    protected $guarded = [];

    public function CaseCategories()
    {
        return $this->hasMany(CaseCategory::class);
    }
}
