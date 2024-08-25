<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $table='invoice_items';
    protected $guarded = [];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function caseRequest()
    {
        return $this->belongsTo(CaseRequest::class);
    }
}
