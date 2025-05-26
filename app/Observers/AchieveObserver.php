<?php

namespace App\Observers;

use App\Models\achieve;
use App\Models\Achievement;
use App\Models\User;
use Pusher\Pusher;

class AchieveObserver
{

    public function WS_Send($channel, $event, $data){
        $options = array(
            'cluster' => 'eu',
            'useTLS' => true,
            'curl_options' => [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ]
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger($channel, $event, $data);
    }

    public function created(achieve $achieve): void
    {
        $user = User::find($achieve->user_id);
        $achievement = Achievement::find($achieve->achievement_id);

        $this->WS_Send('user-Notifier-'.$user->id, 'notify', ["type"=>"Achievement Unlocked", "message"=>$achievement->description]);
    }


    public function updated(achieve $achieve): void
    {
        //
    }

    public function deleted(achieve $achieve): void
    {
        //
    }


    public function restored(achieve $achieve): void
    {
        //
    }

    public function forceDeleted(achieve $achieve): void
    {
        //
    }
}
