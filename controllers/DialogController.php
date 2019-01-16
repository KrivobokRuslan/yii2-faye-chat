<?php

namespace krivobokruslan\fayechat\controllers;

use krivobokruslan\fayechat\forms\DialogCreateForm;
use krivobokruslan\fayechat\useCases\DialogService;
use yii\base\Module;
use yii\web\Controller;

class DialogController extends Controller
{

    private $service;

    public function __construct($id, Module $module, array $config = [], DialogService $service)
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionCreate()
    {
        $form = new DialogCreateForm();

        if ($form->load(\Yii::$app->request->post(), '') && $form->validate()) {
            try {
                $dialog = $this->service->create($form, \Yii::$app->user->id);
                return $this->render('dialog', [
                    'dialog' => $dialog,
                    'currentUserId' => \Yii::$app->user->id
                ]);
            } catch (\DomainException $e) {

            }
        }
    }
}