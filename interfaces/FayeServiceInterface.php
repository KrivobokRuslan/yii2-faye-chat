<?php

namespace krivobokruslan\fayechat\interfaces;

interface FayeServiceInterface
{
    public function send(string $channel, array $data = []): void;
}