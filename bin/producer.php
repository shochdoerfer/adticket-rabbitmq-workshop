<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'direct_routing';
$queue = 'direct_msgs';

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare($queue);
$channel->exchange_declare($exchange, 'direct');
$channel->queue_bind($queue, $exchange);

$messageBody = 'Direct connect';
$message = new AMQPMessage($messageBody);
$channel->basic_publish($message, $exchange);

$channel->close();
$connection->close();
