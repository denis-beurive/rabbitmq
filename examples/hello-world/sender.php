<?php


include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

include_once __DIR__ . DIRECTORY_SEPARATOR . 'configuration.php';

// --------------------------------------------------------
// Connection to the RabbitMq server.
// --------------------------------------------------------

$connection = new AMQPStreamConnection($RABBITMQ_HOST, $RABBITMQ_PORT, $RABBITMQ_LOGIN, $RABBITMQ_PASSWORD);

// --------------------------------------------------------
// Open a channel.
// --------------------------------------------------------

$channel = $connection->channel();

// --------------------------------------------------------
// We create the queue named "hello".
// --------------------------------------------------------

$channel->queue_declare(
    'hello',
    false,
    false,
    false,
    false);

// --------------------------------------------------------
// Let's send messages to the queue.
// --------------------------------------------------------

for ($i = 0; $i<10; $i++) {
    $message = "Message number $i";
    print "Sending $message\n";
    $msg = new AMQPMessage("Message number $i");
    $channel->basic_publish($msg, '', 'hello');
    sleep(3);
}

print "Done\n";


