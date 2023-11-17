<?php

namespace App\Observers;

use App\Models\User;
use DateTimeInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the Admin "updating" event.
     */
    public function updating(User $user): void
    {
        if (request()->get('updated_at') !== $user->getOriginal('updated_at')->format(DateTimeInterface::ATOM)) {
            throw new ConflictHttpException();
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
