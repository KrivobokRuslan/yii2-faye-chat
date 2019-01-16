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
            'POST chat/dialog' => 'faye-chat/dialog/create'
        ], false);

        $app->setModule('faye-chat', ChatModule::class);
    }
}