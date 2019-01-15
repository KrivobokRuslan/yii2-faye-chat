<?php

namespace krivobokruslan\fayechat\interfaces;

interface UserInterface
{
    public function getUserId(): string;

    public function getUsername(): string;

    public function getAvatar(): ?string;
}