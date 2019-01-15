<?php

namespace krivobokruslan\fayechat\useCases;

use krivobokruslan\fayechat\entities\User;
use krivobokruslan\fayechat\interfaces\FayeServiceInterface;
use krivobokruslan\fayechat\interfaces\UserServiceInterface;
use krivobokruslan\fayechat\repositories\UserRepository;

class UserService implements UserServiceInterface
{

    private $repository;
    private $fayeService;

    public function __construct(UserRepository $repository, FayeServiceInterface $fayeService)
    {
        $this->repository = $repository;
        $this->fayeService = $fayeService;
    }

    public function addUser(string $id, string $username, ?string $avatar): void
    {
        $user = User::create($id, $username, $avatar);
        $this->repository->save($user);
        $this->fayeService->send('/new-user', $user->toArray());
    }

    public function removeUser(string $id): void
    {

    }
}