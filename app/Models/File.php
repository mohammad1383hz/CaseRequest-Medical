<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function children()
    {
        return $this->hasMany(File::class, 'parent_id');
    }

    public function FileRequest()
    {
        return $this->belongsToMany(CaseRequest::class, 'case_files');
    }

    public function FileReport()
    {
        return $this->belongsToMany(CaseReport::class, 'case_files');
    }


}
