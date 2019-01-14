<?php

namespace krivobokruslan\fayechat\useCases;

use krivobokruslan\fayechat\repositories\UserRepository;

class DefaultService
{

    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function list(): array
    {
        return $this->users->findAll();
    }
}