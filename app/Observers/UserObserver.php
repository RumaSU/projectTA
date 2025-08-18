<?php

namespace App\Observers;

use App\Models\Users\User;

use App\Jobs\Observer\Users\CreateCertificate;

use App\Utils\LogUtils;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        LogUtils::log(
            'single',
            'observer create user',
            [
                $user->toArray()
            ]
        );
        
        CreateCertificate::dispatch($user)->delay(now()->addSeconds(5));
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
