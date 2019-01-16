<?php

namespace krivobokruslan\fayechat\interfaces;

interface UserInterface
{
    public function getChatUserId(): string;

    public function getChatUsername(): string;

    public function getChatAvatar(): ?string;
}