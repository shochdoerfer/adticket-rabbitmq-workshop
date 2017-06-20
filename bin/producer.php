<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'topic_routing';
$queue = 'topic_msgs';
$routing_key = 'custom.routing';

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare($queue);
$channel->exchange_declare($exchange, 'topic');
$channel->queue_bind($queue, $exchange);

$messageBody = 'Topic Exchange';
$message = new AMQPMessage($messageBody);
$channel->basic_publish($message, $exchange, $routing_key);

$channel->close();
$connection->close();
