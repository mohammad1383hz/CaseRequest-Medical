<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


   

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function caseRequests()
    {
        return $this->hasMany(CaseRequest::class);
    }
    public function caseReportComments()
    {
        return $this->hasMany(CaseReportComment::class);
    }
    public function coupons()
        {
            return $this->belongsToMany(Coupon::class, 'coupon_user');
        }
        public function caseAssignments()
{
    return $this->hasMany(CaseAssignment::class);
}

public function national_cart_id()
    {
        return $this->belongsTo(File::class, 'national_cart_id');
    }

    public function img_1()
    {
        return $this->belongsTo(Currency::class, 'img_1');
    }

    public function img_2()
    {
        return $this->belongsTo(Currency::class, 'img_2');
    }








}
