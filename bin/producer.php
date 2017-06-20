<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'fanout_routing';

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->exchange_declare($exchange, 'fanout');

$messageBody = 'Hello fanout!';
$message = new AMQPMessage($messageBody, ['content_type' => 'text/plain']);
$channel->basic_publish($message, $exchange);

$channel->close();
$connection->close();
