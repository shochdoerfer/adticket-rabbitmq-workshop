<?php

namespace AppBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class DirectConsumer implements ConsumerInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        echo "\n--------\n";
        echo $msg->body;
        echo "\n--------\n";

    }
}
