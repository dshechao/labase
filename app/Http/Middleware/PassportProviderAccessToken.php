<?php
namespace App\Http\Middleware;

use App\Jobs\UpdateUserInfo;
use Closure;
use League\OAuth2\Server\ResourceServer;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class PassportProviderAccessToken
{
    private $server;

    public function __construct(ResourceServer $server)
    {
        $this -> server = $server;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request,Closure $next)
    {
        $psr = (new DiactorosFactory) -> createRequest($request);
        try{
            $psr      = $this -> server -> validateAuthenticatedRequest($psr);
            $token_id = $psr -> getAttribute('oauth_access_token_id');
            if ($token_id){
                $access_token = \DB ::table('oauth_access_token_providers')
                    -> where('oauth_access_token_id',$token_id) -> first()
                ;
                //设置权限服务提供者
                if ($access_token){
                    \Config ::set('auth.guards.api.provider',$access_token -> provider);
                }
                //记录用户最后登录时间和IP
                $userid = $psr -> getAttribute('oauth_user_id');
                if (cache($access_token -> provider."login_info_user_$userid") == null){
                    cache([$access_token -> provider."login_info_user_$userid" => time()],10);
                    UpdateUserInfo ::dispatch(
                        $userid,
                        [
                            'last_ip'   => request() -> ip(),
                            'last_time' => time(),
                        ],
                        $access_token -> provider
                    );
                }
            }
        } catch (\Exception $error){

        }

        return $next($request);
    }
}
