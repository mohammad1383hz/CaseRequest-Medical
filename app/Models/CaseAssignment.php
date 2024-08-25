<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseAssignment extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function caseRequest()
    {
        return $this->belongsTo(CaseRequest::class);
    }
    public function caseReport()
{
    return $this->hasOne(CaseReport::class);
}
}
