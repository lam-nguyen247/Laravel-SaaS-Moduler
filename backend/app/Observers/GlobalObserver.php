<?php

namespace App\Observers;

use App\Models\BaseModel;
use DateTimeInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class GlobalObserver
{
    /**
     * Handle the BaseModel "created" event.
     */
    public function created(BaseModel $baseBaseModel): void
    {
        //
    }

    /**
     * Handle the BaseModel "updating" event.
     */
    public function updating(BaseModel $baseBaseModel): void
    {
        if (request()->get('updated_at') !== $baseBaseModel->getOriginal('updated_at')->format(DateTimeInterface::ATOM)) {
            throw new ConflictHttpException();
        }
    }

    /**
     * Handle the BaseModel "updated" event.
     */
    public function updated(BaseModel $baseBaseModel): void
    {
        //
    }

    /**
     * Handle the BaseModel "deleted" event.
     */
    public function deleted(BaseModel $baseBaseModel): void
    {
        //
    }

    /**
     * Handle the BaseModel "restored" event.
     */
    public function restored(BaseModel $baseBaseModel): void
    {
        //
    }

    /**
     * Handle the BaseModel "force deleted" event.
     */
    public function forceDeleted(BaseModel $baseBaseModel): void
    {
        //
    }
}
