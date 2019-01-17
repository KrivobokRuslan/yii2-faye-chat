<?php

namespace krivobokruslan\fayechat\useCases;

use krivobokruslan\fayechat\entities\User;
use krivobokruslan\fayechat\interfaces\SocketServiceInterface;
use krivobokruslan\fayechat\interfaces\UserServiceInterface;
use krivobokruslan\fayechat\repositories\UserRepository;

class UserService implements UserServiceInterface
{

    private $repository;
    private $socketService;

    public function __construct(UserRepository $repository, SocketServiceInterface $socketService)
    {
        $this->repository = $repository;
        $this->socketService = $socketService;
    }

    public function addUser(string $id, string $username, ?string $avatar): User
    {
        $user = User::create($id, $username, $avatar);
        $this->repository->save($user);
        $this->socketService->send('', ['event' => 'userSignup', 'user' => $user->toArray()]);
        return $user;
    }

    public function removeUser(string $id): void
    {

    }
}