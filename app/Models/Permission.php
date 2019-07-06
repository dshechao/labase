<?php
namespace App\Models;

use Spatie\Permission\Models\Permission as PermissionModel;

class Permission extends PermissionModel
{
    protected $hidden
        = [
            'created_at',
            'pivot',
        ];
}
