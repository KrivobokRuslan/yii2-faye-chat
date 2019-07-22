<?php

namespace krivobokruslan\fayechat\useCases;

use krivobokruslan\fayechat\repositories\RoomRepository;
use krivobokruslan\fayechat\repositories\UserRepository;

class DefaultService
{

    private $users;
    private $rooms;

    public function __construct(
        UserRepository $users,
        RoomRepository $rooms
    )
    {
        $this->users = $users;
        $this->rooms = $rooms;
    }

    public function list(): array
    {
        return $this->users->findAll();
    }

    public function rooms($userId): array
    {
        return $this->rooms->getByUser($userId);
    }
}