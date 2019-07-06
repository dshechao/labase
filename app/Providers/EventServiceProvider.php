<?php
namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen
        = [
            Registered::class                             => [
                SendEmailVerificationNotification::class,
            ],
            //passport_token创建事件
            'Laravel\Passport\Events\AccessTokenCreated'  => [
                'App\Listeners\RevokeOldTokens',
            ],
            //passport_token 刷新事件
            'Laravel\Passport\Events\RefreshTokenCreated' => [
                'App\Listeners\PruneOldTokens',
            ],
        ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent ::boot();
        //
    }
}
