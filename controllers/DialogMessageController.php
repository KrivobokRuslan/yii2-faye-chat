<?php

namespace krivobokruslan\fayechat\controllers;

use krivobokruslan\fayechat\forms\DialogMessageForm;
use krivobokruslan\fayechat\useCases\DialogMessageService;
use yii\base\Module;
use yii\web\Controller;

class DialogMessageController extends Controller
{

    private $service;

    public function __construct($id, Module $module, array $config = [], DialogMessageService $service)
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionCreate()
    {
        $form = new DialogMessageForm();
        if ($form->load(\Yii::$app->request->post(), '') && $form->validate()) {
            $message = $this->service->create($form);
        }
    }
}