<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$exchange = 'direct_routing';
$queue = 'direct_msgs';
$consumerTag = 'consumer';

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare($queue);
$channel->exchange_declare($exchange, 'direct');
$channel->queue_bind($queue, $exchange);

$channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');
register_shutdown_function('shutdown', $channel, $connection);

// Loop as long as the channel has callbacks registered
while (count($channel->callbacks)) {
    $channel->wait();
}
