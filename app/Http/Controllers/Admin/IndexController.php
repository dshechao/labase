<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;

class IndexController extends Controller
{

    /**
     * @return array
     */
    public function user()
    {
        $user = Auth ::user();
        /**@var $user User */
        $user -> role = $user -> roles()
            -> with('permissions:name')
            -> first(['name','id'])
        ;

        return ['result' => $user];
    }

    public function test()
    {
        return 1231313;
    }
}
