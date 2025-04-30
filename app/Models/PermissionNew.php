<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoleNew;

class PermissionNew extends Model
{
    protected $table = 'permissions_new';
    protected $fillable = ['module', 'action'];

    public function roles()
    {
        return $this->belongsToMany(RoleNew::class, 'role_permission_new', 'permission_id', 'role_id');
    }
}

