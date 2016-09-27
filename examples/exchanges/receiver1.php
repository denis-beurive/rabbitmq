<?php

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

include_once __DIR__ . DIRECTORY_SEPARATOR . 'configuration.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';

// ---------------------------------------------------------------
// Connection to the RabbitMq server.
// ---------------------------------------------------------------

$connection = new AMQPStreamConnection($RABBITMQ_HOST, $RABBITMQ_PORT, $RABBITMQ_LOGIN, $RABBITMQ_PASSWORD);

// ---------------------------------------------------------------
// Open a channel.
// ---------------------------------------------------------------

$channel = $connection->channel();

// --------------------------------------------------------
// Declare an exchange named "router".
//
// SEE http://rubybunny.info/articles/exchanges.html
// A direct exchange delivers messages to queues based on a
// message routing key, an attribute that every AMQP v0.9.1
// message contains.
// --------------------------------------------------------

$channel->exchange_declare(Constants::EXCHANGE_NAME, 'direct', false, false, false);

// ---------------------------------------------------------------
// We declare the queue and we bind it to a routing key.
// ---------------------------------------------------------------

$channel->queue_declare(Constants::QUEUE1_NAME, false, false, false, false);
$channel->queue_bind(Constants::QUEUE1_NAME, Constants::EXCHANGE_NAME, Constants::ROUTING_KEY1);


// ---------------------------------------------------------------
// Then, we pick messages from the queue.
// ---------------------------------------------------------------

$callback = function($msg) use ($argv) {
    print '[ ' . $argv[0] . '] Received ' . $msg->body . "\n";
};

$channel->basic_consume(Constants::QUEUE1_NAME, '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

