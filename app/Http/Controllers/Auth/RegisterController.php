<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\PassPortProxy;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use function request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use PassPortProxy;
    private $register_type;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //判断注册方式,伪造注册参数
        $this -> register_type = strstr(request('account'),'@')?'email':'phone';
        request() -> offsetSet($this -> register_type,request('account'));
        if (request('name') == null){
            request() -> offsetSet('name',request('account'));
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     * @throws Exception
     */
    protected function validator(array $data)
    {

        $rule = [
            'name'     => ['required','string','max:255'],
            'password' => ['required','string','min:6','confirmed'],
        ];
        if ($this -> register_type == 'email'){
            $rule['email'] = ['required','string','email','max:255','unique:users'];
        } else {
            $phone     = request('account');
            $ip        = request() -> getClientIp();
            $userAgent = request() -> header('User-Agent');
            $code      = cache("$phone$ip$userAgent");
            if (request('code') != null && $code == request('code')){
                $rule['phone'] = ['required','string','digits:11','unique:users'];
            } else {
                abort('400','验证码错误');
            }
        }

        return Validator ::make($data,$rule);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $data['password'] = Hash ::make($data['password']);

        return User ::create($data);
    }

    /**
     * @return ResponseFactory|Response
     * @throws ValidationException
     * @throws Exception
     */
    public function register()
    {
        $registerData = $this -> validator(request() -> all()) -> validate();
        $this -> create($registerData);
        $token = $this -> getAuthTokenByPassword();

        return response($token);
    }
}
