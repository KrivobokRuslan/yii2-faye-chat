<?php

namespace krivobokruslan\fayechat;

use krivobokruslan\fayechat\interfaces\FayeServiceInterface;
use krivobokruslan\fayechat\services\FayeService;
use yii\base\BootstrapInterface;
use yii\base\Module;

class ChatModule extends Module implements BootstrapInterface
{
    public $controllerNamespace = 'krivobokruslan\fayechat\controllers';

    public $fayeHost = 'http://localhost:8000';

    private $token = 'secret-faye-token';

    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(FayeServiceInterface::class, function() use ($app) {
            /**
             * @var ChatModule $chatModule
             */
            $chatModule = $app->getModule('faye-chat');
            return new FayeService($chatModule->getHost(), $chatModule->getToken());
        });
    }

    public function getHost()
    {
        return $this->fayeHost;
    }

    public function getToken()
    {
        return $this->token;
    }
}