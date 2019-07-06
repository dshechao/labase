<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\PassPortProxy;
use App\Models\Model\RequestModel;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;
use Psr\SimpleCache\InvalidArgumentException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers,PassPortProxy;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $login_way = 'email';

    /**
     * 获取验证码
     *
     * @param RequestModel $http
     *
     * @return array
     * @throws Exception
     */
    public function getCode(RequestModel $http)
    {
        $code      = rand(1111,9999);
        $phone     = Input ::get('phone');
        $existence = User ::where('phone',$phone) -> exists();
        $ip        = request() -> getClientIp();
        $userAgent = request() -> header('User-Agent');
        cache(["$phone$ip$userAgent" => $code],300);
        $sendStatus = $http -> sendSms($code);

        return ['code' => $code,'status' => $sendStatus,'exists' => $existence];
    }

    /**
     * 登录调度
     *
     * @param Request $request
     *
     * @return ResponseFactory|Response
     * @throws ValidationException
     * @throws InvalidArgumentException
     */
    public function yqdLogin(Request $request)
    {
        $grant_type = Input ::get('grant_type');
        switch ($grant_type){

            case 'pwd':
                return response($this -> amountLogin($request));
            case 'code':
                return response($this -> codeLogin());
            case 'wx':
                return response($this -> wxLogin());
            case 'xcx':
                return response($this -> xcxLogin());
            case 'ali':
                return response($this -> aliLogin());
        }

        return abort('450','未知的登录方式');
    }

    /**
     * 账号密码登录(手机号或者邮箱)
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     * @throws ValidationException
     */
    public function amountLogin(Request $request)
    {
        //判断登录方式
        $this -> login_way = strstr(request('account'),'@')?'email':'phone';
        //验证登录信息
        $this -> validateLogin($request);
        //防止爆破
        if ($this -> hasTooManyLoginAttempts($request)){
            $this -> fireLockoutEvent($request);
            $this -> sendLockoutResponse($request);
        }
        //
        if ($this -> attemptLogin($request)){
            //成功返回结果
            $this -> clearLoginAttempts($request);
            $token = $this -> getAuthTokenByPassword();

            return ['result' => $token];
        }
        $this -> incrementLoginAttempts($request);

        return $this -> sendFailedLoginResponse($request);
    }

    /**
     * 管理后台账号密码登录(手机号或者邮箱)
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     * @throws ValidationException
     */
    public function adminLogin(Request $request)
    {
        //判断登录方式
        $this -> login_way = strstr(request('account'),'@')?'email':'phone';
        //验证登录信息
        $this -> validateLogin($request);
        //防止爆破
        if ($this -> hasTooManyLoginAttempts($request)){
            $this -> fireLockoutEvent($request);
            $this -> sendLockoutResponse($request);
        }
        if ($this -> attemptAdminLogin($request)){
            //            \Config ::set('auth.guards.api.provider','admins');
            //成功返回结果
            $this -> clearLoginAttempts($request);
            $token = $this -> getAuthTokenByPassword('admins');

            return ['result' => $token];
        }
        $this -> incrementLoginAttempts($request);

        return $this -> sendFailedLoginResponse($request);
    }

    /**
     * 验证码登录
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function codeLogin()
    {
        $phone     = Input ::get('account');
        $ip        = request() -> getClientIp();
        $userAgent = request() -> header('User-Agent');
        $code      = cache("$phone$ip$userAgent");
        if ($code == request('secret')){
            cache() -> delete("$phone$ip$userAgent");
            $password = User ::where('phone',$phone) -> value('password');
            request() -> offsetSet('secret',$password);
            $token = $this -> getAuthTokenByPassword();

            return ['result' => $token];
        } else {
            abort('400','验证码错误');
        }
    }

    /**
     * 微信登录 验证码登录
     */
    public function wxLogin()
    {
        return ['msg' => "暂未开通"];
    }

    /**
     * 小程序登录
     */
    public function xcxLogin()
    {
        return ['msg' => "暂未开通"];
    }

    /**
     * 支付宝登录
     */
    public function aliLogin()
    {
        return ['msg' => "暂未开通"];
    }

    /**
     * 用户登录方式
     *
     * @return string
     */
    public function username()
    {
        request() -> offsetSet($this -> login_way,request('account'));
        request() -> offsetSet('password',request('secret'));

        return $this -> login_way;
    }

    /**
     * 前台使用刷新 TOKEN 获取新的 ACCESS_TOKEN
     *
     * @return mixed
     *
     */
    public function refreshToken()
    {
        $result = $this -> getTokenByRefreshToken();

        return $result;
    }

    /**
     * 后台使用刷新 TOKEN 获取新的 ACCESS_TOKEN
     *
     * @return mixed
     */
    public function refreshAdminToken()
    {
        $result = $this -> getTokenByRefreshToken('admins');

        return $result;
    }

    public function logout()
    {
        //        $tokenId = \DB ::table('oauth_access_tokens')
        //            -> where('user_id',Auth ::id()) -> value('id')
        //        ;
        //        \DB ::table('oauth_refresh_tokens') -> where('access_token_id',$tokenId) -> delete();
        DB ::table('oauth_access_tokens') -> where('user_id',Auth ::id()) -> delete();

        return ['message' => 'logout success'];
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @param null $guard
     *
     * @return StatefulGuard
     */
    protected function guard($guard = null)
    {
        return Auth ::guard($guard);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function attemptAdminLogin(Request $request)
    {
        return $this -> guard('web_admin') -> attempt(
            $this -> credentials($request),$request -> filled('remember')
        )
            ;
    }
}
