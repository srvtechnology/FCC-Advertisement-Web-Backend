<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PermissionNew;

class RoleNew extends Model
{
    protected $table = 'roles_new';
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->belongsToMany(PermissionNew::class, 'role_permission_new', 'role_id', 'permission_id');
    }
}
