<?php


include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

include_once __DIR__ . DIRECTORY_SEPARATOR . 'configuration.php';

// ---------------------------------------------------------------
// Connection to the RabbitMq server.
// ---------------------------------------------------------------

$connection = new AMQPStreamConnection($RABBITMQ_HOST, $RABBITMQ_PORT, $RABBITMQ_LOGIN, $RABBITMQ_PASSWORD);

// ---------------------------------------------------------------
// Open a channel.
// ---------------------------------------------------------------

$channel = $connection->channel();

// ---------------------------------------------------------------
// We create the (or connect to the) queue named "hello".
// ---------------------------------------------------------------

$channel->queue_declare('hello', false, false, false, false);

// ---------------------------------------------------------------
// Then, we pick messages from the queue.
// ---------------------------------------------------------------

$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

