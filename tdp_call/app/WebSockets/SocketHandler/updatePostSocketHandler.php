<?php

namespace App\WebSockets\SocketHandler;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use BeyondCode\LaravelWebSockets\Apps\App;
use App\Events\WebSocketDataEvent;
// use App\Http\Resources\PostResource;

class updatePostSocketHandler implements MessageComponentInterface
{

    public function onOpen(ConnectionInterface $connection)
    {
        dump("openedddd");
        $socketId = sprintf('%d.%d', random_int(1, 1000000000), random_int(1, 1000000000));
    $connection->socketId = $socketId;
    $connection->app = App::findById('1234');
        // $this->generateSocketId($connection);
        // TODO: Implement onOpen() method.
    }
    
    protected function generateSocketId(ConnectionInterface $connection){
        $socketId = sprintf('%d.%d',random_int(1,10000000000),random_int(1,10000000000));
        $connection->socketId = $socketId;
        return $this; 
    }

    public function onClose(ConnectionInterface $connection)
    {
        dump("connection closed");
        // TODO: Implement onClose() method.
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        dump($e);
        // TODO: Implement onError() method.
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
        $body = collect(json_decode($msg->getPayload(), true));
        dump($body->get('type'));

        // if($body->get('type') == 'call'){
            // WebSocketsBroadcaster::broadcastToChannel('obd-call', ['custom_data' => 'Your custom message data']);

        //  $connection->send(json_encode($body->get('message')));
        // }
        // $response = "hello";

        // $connection->send($response);
    }
}
