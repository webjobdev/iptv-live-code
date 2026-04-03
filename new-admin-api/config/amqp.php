<?php
return [

'use' => 'production',

'properties' => [

    'production' => [
        'host'                => env('RABBITMQ_HOST', '0.0.0.0'),
        'port'                => env('RABBITMQ_PORT', 15672),
        'username'            => env('RABBITMQ_USERNAME', 'guest'),
        'password'            => env('RABBITMQ_PASSWORD', 'guest'),
        'vhost'               => env('RABBITMQ_VIRTUAL_HOST', '/'),
        'exchange'            => 'amq.topic',
        'exchange_type'       => 'topic',
        'exchange_durable'      => true,
        'consumer_tag'        => 'consumer',
        'ssl_options'         => [], // See https://secure.php.net/manual/en/context.ssl.php
        'connect_options'     => [], // See https://github.com/php-amqplib/php-amqplib/blob/master/PhpAmqpLib/Connection/AMQPSSLConnection.php
        'queue_properties'    => ['x-ha-policy' => ['S', 'all'],'x-queue-type' => ['S','classic']],
        'queue_durable'         => true,
        'exchange_properties' => [],
        'timeout'             => 0,
    ],

],

];