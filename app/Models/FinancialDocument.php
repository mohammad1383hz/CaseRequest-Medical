<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialDocument extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function creditorAccount()
    {
        return $this->belongsTo(FinancialAccount::class, 'creditor_id');
    }

    // رابطه با جدول financial-account برای debtor
    public function debtorAccount()
    {
        return $this->belongsTo(FinancialAccount::class, 'debtor_id');
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
