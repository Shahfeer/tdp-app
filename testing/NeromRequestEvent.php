
<?php

namespace App\Events;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class NeronRequestEvent implements ShouldBroadcast
{

   use Dispatchable, InteractsWithSockets, SerializesModels;

     public $requestData;


    public function __construct($requestData)
    {
     	$this->requestData = $requestData;
    }



    public function broadcastOn()
    {
     	return new Channel('neron-request'); // Set the name of the WebSocket channel
    }


}
