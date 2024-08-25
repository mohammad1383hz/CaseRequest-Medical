<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusNotification extends Model
{
    use HasFactory;
    protected $table='status_notifications';
    protected $guarded = [];
}
