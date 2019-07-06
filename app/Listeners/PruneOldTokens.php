<?php
namespace App\Listeners;

class PruneOldTokens
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 生成了新的刷新token,删除旧的
     * Handle the event.
     *
     *
     * @return void
     */
    public function handle()
    {
        \DB ::table('oauth_refresh_tokens')
            -> where('revoked',1)
            -> orWhere('expires_at','<',now())
            -> delete()
        ;
    }
}
