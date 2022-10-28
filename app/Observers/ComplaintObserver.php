<?php

namespace App\Observers;

use App\Facades\ChatRepository;
use App\Models\Complaint;

class ComplaintObserver
{
    /**
     * Handle the Complaint "created" event.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return void
     */
    public function created(Complaint $complaint)
    {
        ChatRepository::createIfNotExists(['chateable_id' => $complaint->id, 'chateable_type' => Complaint::class]);
    }

    /**
     * Handle the Complaint "updated" event.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return void
     */
    public function updated(Complaint $complaint)
    {
        //
    }

    /**
     * Handle the Complaint "deleted" event.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return void
     */
    public function deleted(Complaint $complaint)
    {
        //
    }

    /**
     * Handle the Complaint "restored" event.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return void
     */
    public function restored(Complaint $complaint)
    {
        //
    }

    /**
     * Handle the Complaint "force deleted" event.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return void
     */
    public function forceDeleted(Complaint $complaint)
    {
        //
    }
}
