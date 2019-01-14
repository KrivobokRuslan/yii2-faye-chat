<?php

namespace krivobokruslan\fayechat\interfaces;

interface UserServiceInterface
{
    public function addUser(string $id, string $username, string $avatar = ''): void;

    public function removeUser(string $id): void;
}