<?php

namespace krivobokruslan\fayechat\interfaces;

use krivobokruslan\fayechat\entities\User;

interface UserServiceInterface
{
    public function addUser(UserInterface $user): User;

    public function removeUser(string $id): void;
}