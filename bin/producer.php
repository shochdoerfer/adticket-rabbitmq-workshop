<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

$dlx_exchange = 'dead_letter';
$dlx_queue = 'dead_letter_msgs';
$dlx_exchange = 'dead_letter';
$exchange = 'dlx_demo';
$queue = 'dlx_msgs';

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');

// declare dead-letter exchange
$channel = $connection->channel();
$channel->queue_declare($dlx_queue);
$channel->exchange_declare($dlx_exchange, 'direct');
$channel->queue_bind($dlx_queue, $dlx_exchange);

// declare regular queue
$channel = $connection->channel();
$channel->queue_declare($queue, false, false, false, true, false, new AMQPTable(['x-dead-letter-exchange' => $dlx_exchange, 'x-message-ttl' => 60000]));
$channel->exchange_declare($exchange, 'direct');
$channel->queue_bind($queue, $exchange);

$messageBody = 'DXL Message';
$message = new AMQPMessage($messageBody);
$channel->basic_publish($message, $exchange);

$channel->close();
$connection->close();
