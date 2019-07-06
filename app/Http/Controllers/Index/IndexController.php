<?php
namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Imports\DataImport;
use App\Models\Permission;
use App\Models\Role;
use Auth;
use Excel;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class IndexController extends Controller
{
    protected $guard = 'admins';

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('welcome');
    }

    public function user()
    {
        return response(Auth ::user());
    }

    /**
     *
     */
    public function debug()
    {
        $fileName = './yqd/yqd.xlsx';
        Excel ::import(new DataImport(),$fileName);
    }

    public function debugc()
    {
        $role       = Role ::create(['name' => 'writer']);
        $permission = Permission ::create(['name' => 'edit articles']);
        $permission -> assignRole($role);
        dd("ok");
    }

}
