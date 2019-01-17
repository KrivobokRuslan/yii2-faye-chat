<?php

namespace krivobokruslan\fayechat\interfaces;

interface SocketServiceInterface
{
    public function send(string $channel, array $data = []): void;
}