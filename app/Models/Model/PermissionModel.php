<?php
namespace App\Models\Model;

use App\Models\BaseModel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class PermissionModel extends BaseModel
{
    use HasRoles;

    public function index()
    {
        $permissions = Permission ::all();
        $roles       = Role ::all();
        dd($permissions,$roles);
    }

    /**
     * 添加权限
     *
     * @param string $permissionName 权限名
     *
     * @return $this
     */
    public function createPermission($permissionName)
    {
        Permission ::create(['name' => $permissionName]);

        return $this;
    }

    /**
     * 添加角色
     *
     * @param string $roleName 角色名
     *
     * @return $this
     */
    public function createRole($roleName)
    {
        Role ::create(['name' => $roleName]);

        return $this;
    }

    /**
     * 给角色赋予权限
     *
     * @param string $roleName       角色名
     * @param string $permissionName 权限名
     */
    public function getPermissionToRole($roleName,$permissionName)
    {
        $role = Role ::findByName($roleName);
        $role -> givePermissionTo($permissionName);
    }

}
