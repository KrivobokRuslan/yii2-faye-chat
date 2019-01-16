<?php

namespace krivobokruslan\fayechat\repositories;

use krivobokruslan\fayechat\entities\User;

class UserRepository
{
    public function findAll()
    {
        return User::find()->active()->notCurrentUser()->all();
    }

    public function save(User $user)
    {
        if (!$user->save()) {
            throw new \DomainException('Saving error');
        }
    }
}