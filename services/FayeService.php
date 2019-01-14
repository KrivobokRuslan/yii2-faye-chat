<?php

namespace krivobokruslan\fayechat\services;

use krivobokruslan\fayechat\interfaces\FayeServiceInterface;
use Nc\FayeClient\Adapter\CurlAdapter;
use Nc\FayeClient\Client;

class FayeService implements FayeServiceInterface
{

    private $client;
    private $adapter;
    private $token;

    public function __construct(string $host, string $token)
    {
        $this->adapter = new CurlAdapter();
        $this->client = new Client($this->adapter, $host);
        $this->token = $token;
    }

    public function send(string $channel, array $data = []): void
    {
        $this->client->send($channel, $data, [
            'token' => $this->token
        ]);
    }
}