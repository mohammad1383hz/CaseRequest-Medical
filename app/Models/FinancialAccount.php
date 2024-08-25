<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialAccount extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function creditorDocuments()
    {
        return $this->hasMany(FinancialDocument::class, 'creditor_id');
    }

    public function debtorDocuments()
    {
        return $this->hasMany(FinancialDocument::class, 'debtor_id');
    }
}
