<?php

namespace krivobokruslan\fayechat;

use krivobokruslan\fayechat\interfaces\SocketServiceInterface;
use krivobokruslan\fayechat\interfaces\UserInterface;
use krivobokruslan\fayechat\services\WorkerService;
use yii\base\Module;

class ChatModule extends Module
{
    public $controllerNamespace = 'krivobokruslan\fayechat\controllers';

    public $tcp_host = 'tcp://127.0.0.1:1234';

    public $ws_host = 'websocket://0.0.0.0:8000';

    public $client_host = 'ws://127.0.0.1:8000';

    private $token = 'secret-faye-token';

    private $authUser;

    public function init()
    {
        parent::init();

        \Yii::$container->setSingleton(SocketServiceInterface::class, function($app) {
            return new WorkerService($this->getTcpHost());
        });

        $this->authUser = \Yii::$app->user->getIdentity();

        if (!($this->authUser instanceof UserInterface)) {
            throw new \DomainException('Your Identity class must implements ' . UserInterface::class);
        }
    }

    public function getTcpHost(): string
    {
        return $this->tcp_host;
    }

    public function getWsHost(): string
    {
        return $this->ws_host;
    }

    public function getClientHost(): string
    {
        return $this->client_host;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUser(): UserInterface
    {
        return $this->authUser;
    }
}