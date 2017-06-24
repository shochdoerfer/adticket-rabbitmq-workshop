<?php

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

function process_message(AMQPMessage $request)
{
    echo "\n--------\n";
    echo $request->body;
    echo "\n--------\n";

    $reply = new AMQPMessage(strrev($request->body), ['correlation_id' => $request->get('correlation_id')]);

    $request->delivery_info['channel']->basic_publish($reply, '', $request->get('reply_to'));
    $request->delivery_info['channel']->basic_ack($request->delivery_info['delivery_tag']);
}

function shutdown(AMQPChannel $channel, AbstractConnection $connection)
{
    $channel->close();
    $connection->close();
}
