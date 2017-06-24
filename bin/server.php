<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$queue = 'rpc_msgs';

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare($queue, false, false, false, false);
$channel->basic_qos(null, 1, null);
$channel->basic_consume($queue, '', false, false, false, false, 'process_message');

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
