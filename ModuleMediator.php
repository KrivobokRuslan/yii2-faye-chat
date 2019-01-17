<?php

namespace krivobokruslan\fayechat;

use krivobokruslan\fayechat\interfaces\SocketServiceInterface;
use krivobokruslan\fayechat\interfaces\UserCreateEventInterface;
use krivobokruslan\fayechat\services\SocketService;
use krivobokruslan\fayechat\services\WorkerService;
use krivobokruslan\fayechat\useCases\UserService;

class ModuleMediator
{
    /**
     * @param UserCreateEventInterface $event
     */
    public static function onUserCreate(UserCreateEventInterface $event)
    {
        /**
         * @var UserService $chatUserService
         */

        \Yii::$container->setSingleton(SocketServiceInterface::class, function($app) {
            /**
             * @var ChatModule $chatModule
             */
            $chatModule = \Yii::$app->getModule('faye-chat');
            return new WorkerService($chatModule->getTcpHost());
        });

        $user = $event->getUser();
        $chatUserService = \Yii::$container->get(UserService::class);
        $chatUserService->addUser($user->getChatUserId(), $user->getChatUsername(), $user->getChatAvatar());
    }
}