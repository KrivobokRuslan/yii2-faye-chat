<?php

namespace krivobokruslan\fayechat;

use krivobokruslan\fayechat\interfaces\FayeServiceInterface;
use krivobokruslan\fayechat\interfaces\UserCreateEventInterface;
use krivobokruslan\fayechat\services\FayeService;
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

        \Yii::$container->setSingleton(FayeServiceInterface::class, function($app) {
            /**
             * @var ChatModule $chatModule
             */
            $chatModule = \Yii::$app->getModule('faye-chat');
            return new FayeService($chatModule->getHost(), $chatModule->getToken());
        });

        $user = $event->getUser();
        $chatUserService = \Yii::$container->get(UserService::class);
        $chatUserService->addUser($user->getUserId(), $user->getUsername(), $user->getAvatar());
    }
}