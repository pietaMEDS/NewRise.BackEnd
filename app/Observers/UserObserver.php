<?php

namespace App\Observers;

use App\Models\achieve;
use App\Models\Achievement;
use App\Models\Auto_achieve;
use App\Models\User;

class UserObserver
{

    public function created(User $user): void
    {
        $autoAchieves = Auto_achieve::where('email', $user->email)->get();

        foreach ($autoAchieves as $auto) {
            achieve::create([
                'user_id' => $user->id,
                'achievement_id' => $auto->achievement_id,
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
