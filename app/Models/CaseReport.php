<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseReport extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function assignment()
    {
        return $this->belongsTo(CaseAssignment::class, 'case_assignment_id');
    }
    public function caseFiles()
{
    return $this->hasMany(CaseFile::class);
}
}
