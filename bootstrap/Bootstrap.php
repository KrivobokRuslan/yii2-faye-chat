<?php

namespace krivobokruslan\fayechat\bootstrap;

use krivobokruslan\fayechat\ChatModule;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            'chat' => 'faye-chat/default/index',
            'POST chat/dialog' => 'faye-chat/dialog/create',
            'POST chat/<dialogId:\d+>/send' => 'faye-chat/dialog-message/create',
            'POST chat/room' => 'faye-chat/room/create',
            'GET chat/room/<roomId:\d+>' => 'faye-chat/room/view',
            'POST room/<roomId:\d+>/send' => 'faye-chat/room-message/create',
            'POST room/<roomId:\d+>/ban' => 'faye-chat/room/ban',
            'GET room/<roomId:\d+>/leave' => 'faye-chat/room/leave',
            'DELETE room/<roomId:\d+>' => 'faye-chat/room/delete',
            'POST chat/room/<roomId:\d+>/add-members' => 'faye-chat/room/add-members'
        ], false);
    }
}