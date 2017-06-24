<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AmqRpcClient
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;
    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;
    /**
     * @var string
     */
    private $callback_queue;
    /**
     * @var string
     */
    private $response;
    /**
     * @var string
     */
    private $corr_id;
    /**
     * @var string
     */
    private $queue;

    public function __construct()
    {
        $this->queue = 'rpc_msgs';
        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        list($this->callback_queue, ,) = $this->channel->queue_declare('', false, false, true, false);
        $this->channel->basic_consume($this->callback_queue, '', false, false, false, false, [$this, 'on_response']);
    }

    public function on_response($rep): void
    {
        if ($rep->get('correlation_id') === $this->corr_id) {
            $this->response = $rep->body;
        }
    }

    public function call(string $n): string
    {
        $this->response = null;
        $this->corr_id = uniqid('rmq', true);

        $msg = new AMQPMessage($n, ['correlation_id' => $this->corr_id, 'reply_to' => $this->callback_queue]);
        $this->channel->basic_publish($msg, '', $this->queue);
        while (!$this->response) {
            $this->channel->wait();
        }
        return $this->response;
    }
}

$client = new AmqRpcClient();
echo "\n--------\n";
echo $client->call('Hello RPC!');
echo "\n--------\n\n";
