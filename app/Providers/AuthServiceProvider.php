<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies
        = [
            'App\Model' => 'App\Policies\ModelPolicy',
        ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this -> registerPolicies();
        //注册 passport
        Passport ::routes(function (RouteRegistrar $router){
            $router -> forAccessTokens();
        },['prefix' => 'oauth','middleware' => 'passport-admin']);
        //设置令牌有效期
        Passport ::tokensExpireIn(now() -> addDays(15));
        //设置刷新有效期
        Passport ::refreshTokensExpireIn(now() -> addDays(30));
    }
}
