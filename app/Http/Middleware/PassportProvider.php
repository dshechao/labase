<?php
namespace App\Http\Middleware;

use Closure;

class PassportProvider
{
    /**
     * Handle an incoming request.
     * passport 获取 token时设置服务提供者
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request,Closure $next)
    {
        $params = $request -> all();
        if (array_key_exists('provider',$params)){
            \Config ::set('auth.guards.api.provider',$params['provider']);
        }

        return $next($request);
    }
}
