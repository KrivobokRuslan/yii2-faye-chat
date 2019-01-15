<?php

namespace krivobokruslan\fayechat\interfaces;

interface UserCreateEventInterface
{
    public function getUser(): UserInterface;
}