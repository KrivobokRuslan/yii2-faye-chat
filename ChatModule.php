<?php

namespace krivobokruslan\fayechat;

use yii\base\Module;

class ChatModule extends Module
{
    public $controllerNamespace = 'krivobokruslan\fayechat\controllers';

    public $fayeHost = 'http://localhost:8000';

    private $token = 'secret-faye-token';

    public function getHost()
    {
        return $this->fayeHost;
    }

    public function getToken()
    {
        return $this->token;
    }
}