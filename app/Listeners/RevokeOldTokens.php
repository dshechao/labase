<?php
namespace App\Listeners;

use Carbon\Carbon;

class RevokeOldTokens
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
     * 生成了新的 token,删除旧的
     * Handle the event.
     *
     * @param  object $event
     *
     * @return void
     */
    public function handle($event)
    {
        $provider = \Config ::get('auth.guards.api.provider');
        $clientId = \Config ::get("passport.$provider.client_id",'user');
        \DB ::table('oauth_access_tokens')
            -> where([
                ['user_id','=',$event -> userId],
                ['id','<>',$event -> tokenId],
                ['client_id','=',$clientId],
            ])-> delete()
        ;
        \DB ::table('oauth_access_token_providers') -> insert([
            "oauth_access_token_id" => $event -> tokenId,
            "provider"              => $provider,
            "created_at"            => new Carbon(),
            "updated_at"            => new Carbon(),
        ])
        ;
    }
}
