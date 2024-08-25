<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseReportComment extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function caseReport()
    {
        return $this->belongsTo(CaseReport::class);
    }
    

}
