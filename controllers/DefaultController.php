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
use yii\web\Response;

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
        $user = $this->userService->addUser($this->module->getUser());

        return $this->render('index', [
            'user' => $user,
            'users' => $this->service->list(),
            'clientHost' => $this->module->getClientHost()
        ]);
    }
}