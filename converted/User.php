<?php

namespace krivobokruslan\fayechat\converted;

use \krivobokruslan\fayechat\entities\User as pUser;

class User
{
    public $id;
    public $username;
    public $avatar;

    public function __construct(pUser $user)
    {
        $this->id = $user->getChatUserId();
        $this->username = $user->getChatUsername();
        $this->avatar = $user->getChatAvatar();
    }
}