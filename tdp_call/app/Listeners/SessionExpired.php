<?php

namespace App\Listeners;

use IlluminateSessionEventsLifetimeMissed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SessionExpired
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
     * Handle the event.
     *
     * @param  IlluminateSessionEventsLifetimeMissed  $event
     * @return void
     */
    public function handle(IlluminateSessionEventsLifetimeMissed $event)
    {
        //
    }
}
