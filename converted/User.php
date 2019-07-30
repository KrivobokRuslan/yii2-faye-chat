<?php

namespace krivobokruslan\fayechat\converted;

use \krivobokruslan\fayechat\entities\User as pUser;
use yii\base\Model;

class User extends Model
{
    public $id;
    public $username;
    public $avatar;

    public function __construct(pUser $user, array $config = [])
    {
        parent::__construct($config);
        $this->id = $user->getChatUserId();
        $this->username = $user->getChatUsername();
        $this->avatar = $user->getChatAvatar();
    }
}