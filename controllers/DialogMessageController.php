<?php

namespace krivobokruslan\fayechat\controllers;

use krivobokruslan\fayechat\entities\Dialog;
use krivobokruslan\fayechat\exceptions\NotFoundException;
use krivobokruslan\fayechat\forms\DialogMessageForm;
use krivobokruslan\fayechat\useCases\DialogMessageService;
use yii\base\Module;
use yii\web\Controller;
use yii\web\Response;

class DialogMessageController extends MainController
{

    private $service;

    public function __construct($id, Module $module, array $config = [], DialogMessageService $service)
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionCreate($dialogId)
    {
        try {
            $this->findDialog($dialogId);
        } catch (NotFoundException $e) {
            return $this->setErrorStatus($e->getMessage());
        }
        $form = new DialogMessageForm();
        if ($form->load(\Yii::$app->request->post(), '') && $form->validate()) {
            try {
                $message = $this->service->create($form, $dialogId, \Yii::$app->user->id);
                return $this->renderPartial('//dialog/_message', [
                    'message' => $message,
                    'userId' => \Yii::$app->user->id
                ]);
            } catch (\DomainException $e) {
                return $this->setErrorStatus($e->getMessage());
            }

        }
    }

    protected function findDialog($id)
    {
        if (($dialog = Dialog::find()->where(['id' => $id])->one()) !== null) {
            return $dialog;
        }
        throw new NotFoundException();
    }
}