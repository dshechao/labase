<?php
namespace App\Models;

use Spatie\Permission\Models\Role as RoleModle;

class Role extends RoleModle
{
    protected $hidden
        = [
            'created_at',
            'pivot',
        ];
}
