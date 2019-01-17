<?php

namespace krivobokruslan\fayechat\controllers;

use krivobokruslan\fayechat\ChatModule;
use krivobokruslan\fayechat\entities\User;
use krivobokruslan\fayechat\exceptions\NotFoundException;
use krivobokruslan\fayechat\interfaces\UserInterface;
use krivobokruslan\fayechat\useCases\DefaultService;
use krivobokruslan\fayechat\useCases\UserService;
use yii\base\Module;
use yii\web\Controller;

/**
 * @property ChatModule $module
 */

class DefaultController extends Controller
{

    private $service;
    private $userService;

    public function __construct($id, Module $module, array $config = [], DefaultService $service, UserService $userService)
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->userService = $userService;

    }

    public function actionIndex()
    {
        try {
            $user = $this->findUser(\Yii::$app->user->id);
        } catch (NotFoundException $e) {
            /**
             * @var UserInterface $authUser
             */
            $authUser = \Yii::$app->user->getIdentity();
            if (!($authUser instanceof UserInterface)) {
                throw new \DomainException('Your Identity class ('.get_class($authUser).') must implements UserInterface');
            }
            $user = $this->userService->addUser($authUser->getChatUserId(), $authUser->getChatUsername(), $authUser->getChatAvatar());
        }

        return $this->render('index', [
            'user' => $user,
            'users' => $this->service->list(),
            'clientHost' => $this->module->getClientHost()
        ]);
    }

    protected function findUser($id)
    {
        if (($user = User::find()->current()->one()) !== null) {
            return $user;
        }
        throw new NotFoundException();
    }
}