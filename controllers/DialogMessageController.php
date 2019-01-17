<?php

namespace krivobokruslan\fayechat\controllers;

use krivobokruslan\fayechat\entities\Dialog;
use krivobokruslan\fayechat\exceptions\NotFoundException;
use krivobokruslan\fayechat\forms\DialogMessageForm;
use krivobokruslan\fayechat\useCases\DialogMessageService;
use yii\base\Module;
use yii\web\Controller;
use yii\web\Response;

class DialogMessageController extends Controller
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
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
        $form = new DialogMessageForm();
        if ($form->load(\Yii::$app->request->post(), '') && $form->validate()) {
            try {
                $message = $this->service->create($form, $dialogId);
                return $this->renderPartial('../dialog/_message', [
                    'message' => $message
                ]);
            } catch (\DomainException $e) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'status' => false,
                    'error' => $e->getMessage()
                ];
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