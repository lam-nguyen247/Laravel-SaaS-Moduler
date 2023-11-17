<?php

namespace App\Observers;

use App\Models\Admin;
use DateTimeInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class AdminObserver
{
    /**
     * Handle the Admin "created" event.
     */
    public function created(Admin $admin): void
    {
        //
    }

    /**
     * Handle the Admin "updating" event.
     */
    public function updating(Admin $admin): void
    {
        if (request()->get('updated_at') !== $admin->getOriginal('updated_at')->format(DateTimeInterface::ATOM)) {
            throw new ConflictHttpException();
        }
    }

    /**
     * Handle the Admin "updated" event.
     */
    public function updated(Admin $admin): void
    {
        //
    }

    /**
     * Handle the Admin "deleted" event.
     */
    public function deleted(Admin $admin): void
    {
        //
    }

    /**
     * Handle the Admin "restored" event.
     */
    public function restored(Admin $admin): void
    {
        //
    }

    /**
     * Handle the Admin "force deleted" event.
     */
    public function forceDeleted(Admin $admin): void
    {
        //
    }
}
