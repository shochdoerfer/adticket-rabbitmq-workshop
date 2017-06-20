<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'direct_logs';
$queue = 'direct_logs_msgs';
$routingKeys = ['debug', 'info', 'notice', 'test', 'test2'];

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare($queue);
$channel->exchange_declare($exchange, 'direct');
$channel->queue_bind($queue, $exchange);

for($i=0; $i < 100; $i++) {
    $idx = mt_rand(0, count($routingKeys) - 1);

    $messageBody = $routingKeys[$idx]. ' message';
    $message = new AMQPMessage($messageBody);
    $channel->basic_publish($message, $exchange, $routingKeys[$idx]);
}

$channel->close();
$connection->close();
