<?php

namespace krivobokruslan\fayechat\repositories;

use krivobokruslan\fayechat\entities\User;

class UserRepository
{
    public function findUser($id): ?User
    {
        return User::find()->active()->byId($id)->one();
    }

    public function findAll(): ?array
    {
        return User::find()->active()->notCurrentUser()->all();
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \DomainException('Saving error');
        }
    }
}