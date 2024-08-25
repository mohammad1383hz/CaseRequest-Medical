<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FCMUser extends Model
{
    protected $table = 'fcm_user'; // assuming 'fcm_user' is the name of the table

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}