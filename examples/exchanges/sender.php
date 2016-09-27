<?php

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

include_once __DIR__ . DIRECTORY_SEPARATOR . 'configuration.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';

// --------------------------------------------------------
// Connection to the RabbitMq server.
// --------------------------------------------------------

$connection = new AMQPStreamConnection($RABBITMQ_HOST, $RABBITMQ_PORT, $RABBITMQ_LOGIN, $RABBITMQ_PASSWORD);

// --------------------------------------------------------
// Open a channel.
// --------------------------------------------------------

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

// --------------------------------------------------------
// Let's send messages to the queue.
// --------------------------------------------------------

$routingKeys = array(
    Constants::ROUTING_KEY1,
    Constants::ROUTING_KEY2,
    Constants::ROUTING_KEY3
);

$index = 0;
$i     = 1;
while (true) {
    $routingKey = $routingKeys[$index];
    $index = ($index + 1) % count($routingKeys);

    $message = "Message number $i";
    print "Sending $message\n";
    $msg = new AMQPMessage("Message number $i for route key <$routingKey>");
    $channel->basic_publish(
        $msg,                      // Message
        Constants::EXCHANGE_NAME,  // Exchange
        $routingKey                // Routing key
    );
    sleep(3);
    $i++;
}



