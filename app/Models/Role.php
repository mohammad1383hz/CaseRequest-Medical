<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Role extends Model
{
    use HasFactory;
    protected $guarded = [];
    // public function givePermissions(array $permissionIds)
    // {
    //     // اتصال هر شناسه پرمیشن به این نقش
    //     $this->permissions()->sync($permissionIds);
    // }

    // /**
    //  * تعریف ارتباط بین نقش و پرمیشن‌ها
    //  */
    // public function permissions()
    // {
    //     return $this->morphToMany(Permission::class, 'model', 'model_has_roles', 'model_type', 'model_id');
    // }
}
