<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUserInfo implements ShouldQueue
{
    use Dispatchable,InteractsWithQueue,Queueable,SerializesModels;
    private $userId;
    private $params;
    private $guard;

    /**
     * UpdateUserInfo constructor.
     *
     * @param $userID
     * @param $params
     * @param $guard
     */
    public function __construct($userID,$params,$guard = null)
    {
        $this -> userId = $userID;
        $this -> params = $params;
        $this -> guard  = $guard ?? \Config ::get('auth.guards.api.provider');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \DB ::table($this -> guard) -> where('id',$this -> userId)
            -> update($this -> params)
        ;
    }
}
