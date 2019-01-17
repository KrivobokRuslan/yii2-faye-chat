<?php

namespace krivobokruslan\fayechat;

use yii\base\Module;

class ChatModule extends Module
{
    public $controllerNamespace = 'krivobokruslan\fayechat\controllers';

    public $tcp_host = 'tcp://127.0.0.1:1234';

    public $ws_host = 'websocket://0.0.0.0:8000';

    public $client_host = 'ws://127.0.0.1:8000';

    private $token = 'secret-faye-token';

    public function getTcpHost()
    {
        return $this->tcp_host;
    }

    public function getWsHost()
    {
        return $this->ws_host;
    }

    public function getClientHost()
    {
        return $this->client_host;
    }

    public function getToken()
    {
        return $this->token;
    }
}