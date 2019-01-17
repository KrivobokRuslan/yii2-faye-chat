<?php

namespace krivobokruslan\fayechat\interfaces;

use krivobokruslan\fayechat\entities\User;

interface UserServiceInterface
{
    public function addUser(string $id, string $username, ?string $avatar): User;

    public function removeUser(string $id): void;
}