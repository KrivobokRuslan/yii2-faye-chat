<?php

namespace krivobokruslan\fayechat\controllers;

use krivobokruslan\fayechat\ChatModule;
use krivobokruslan\fayechat\useCases\DefaultService;
use yii\base\Module;
use yii\web\Controller;

/**
 * @property ChatModule $module
 */

class DefaultController extends Controller
{

    private $service;

    public function __construct($id, Module $module, array $config = [], DefaultService $service)
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;

    }

    public function actionIndex()
    {
        return $this->render('index', [
            'users' => $this->service->list(),
            'clientHost' => $this->module->getClientHost()
        ]);
    }
}