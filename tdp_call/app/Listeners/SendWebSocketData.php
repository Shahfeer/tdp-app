<?php

namespace App\Listeners;

use App\Events\WebSocketDataEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWebSocketData implements ShouldQueue
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
     * @param  WebSocketDataEvent  $event
     * @return void
     */
    public function handle(WebSocketDataEvent $event)
    {
        WebSocketsRouter::broadcastToAll(json_encode($event->message));
    }
}
