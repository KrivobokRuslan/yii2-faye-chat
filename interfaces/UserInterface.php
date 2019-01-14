<?php

namespace krivobokruslan\fayechat\interfaces;

interface UserInterface
{
    public function getId(): string ;

    public function getUsername(): string ;

    public function getAvatar(): ?string ;
}