<?php

namespace krivobokruslan\fayechat\services;

use krivobokruslan\fayechat\interfaces\SocketServiceInterface;

class WorkerService implements SocketServiceInterface
{

    private $host;

    public function __construct(string $host)
    {
        $this->host = $host;
    }

    public function send(string $channel, array $data = []): void
    {
        $instance = stream_socket_client($this->host);

        fwrite($instance, json_encode($data)  . "\n");
    }
}