<?php

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

function process_message(AMQPMessage $message)
{
    echo "\n--------\n";
    echo $message->delivery_info['routing_key'], ':', $message->body;
    echo "\n--------\n";

    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
}

function shutdown(AMQPChannel $channel, AbstractConnection $connection)
{
    $channel->close();
    $connection->close();
}
