<?php

namespace krivobokruslan\fayechat;

use yii\base\Module;

class ChatModule extends Module
{
    public $controllerNamespace = 'krivobokruslan\fayechat\controllers';

    public $host = 'tcp://127.0.0.1:1234';

    private $token = 'secret-faye-token';

    public function getHost()
    {
        return $this->host;
    }

    public function getToken()
    {
        return $this->token;
    }
}