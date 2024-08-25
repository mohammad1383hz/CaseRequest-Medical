<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        // اعمال مرتب‌سازی پیش‌فرض
        static::addGlobalScope('defaultOrder', function ($builder) {
            $builder->orderByDesc('id');
        });
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user');
    }
}
