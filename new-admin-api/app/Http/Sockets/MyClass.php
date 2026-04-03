<?php

namespace App\Http\Sockets;

use Orchid\Socket\BaseSocketListener;
use Ratchet\ConnectionInterface;

class MyClass extends BaseSocketListener
{
    /**
     * Current clients.
     *
     * @var \SplObjectStorage
     */
    protected $clients;

    /**
     * MyClass constructor.
     */
    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
      
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {  //print_r($conn->getId());

        $this->clients->attach($conn);
    }

    /**
     * @param ConnectionInterface $from
     * @param $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    { // $client->send($msg);
        foreach ($this->clients as $client) {
            $client->send('I am fine what about you');
            if ($from != $client) {
                $client->send($msg);
            }
        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    { print_r('c');
        $this->clients->detach($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception          $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}
