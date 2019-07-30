<?php

namespace krivobokruslan\fayechat\controllers;

use krivobokruslan\fayechat\forms\RoomForm;
use krivobokruslan\fayechat\forms\RoomMembersForm;
use krivobokruslan\fayechat\useCases\RoomService;
use yii\base\Module;

class RoomController extends MainController
{
    private $service;

    public function __construct($id, Module $module, array $config = [], RoomService $service)
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionCreate()
    {
        $this->setResponseJsonFormat();
        $form = new RoomForm();
        $form->addOwner(\Yii::$app->user->id);
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->create($form, \Yii::$app->user->id);
                return $this->setSuccessStatus();
            } catch (\DomainException $e) {
                return $this->setErrorStatus($e->getMessage());
            }
        }
    }

    public function actionView($roomId)
    {
        $roomMembersForm = new RoomMembersForm();
        return $this->renderAjax('room', [
            'room' => $this->service->view($roomId, \Yii::$app->user->id),
            'userId' => \Yii::$app->user->id,
            'users' => $this->service->usersList(),
            'roomMembersForm' => $roomMembersForm
        ]);
    }

    public function actionBan($roomId)
    {
        $form = new RoomMembersForm();

        if ($form->load(\Yii::$app->request->getBodyParams(), '') && $form->validate()) {
            try {
                $this->service->ban($form, $roomId, \Yii::$app->user->id);
                return $this->setSuccessStatus();
            } catch (\DomainException $e) {
                return $this->setErrorStatus($e->getMessage());
            }
        }
    }

    public function actionLeave($roomId)
    {
        try {
            $this->service->leave($roomId, \Yii::$app->user->id);
            return $this->setSuccessStatus();
        } catch (\DomainException $e) {
            return $this->setErrorStatus($e->getMessage());
        }
    }

    public function actionDelete($roomId)
    {
        try {
            $this->service->delete($roomId, \Yii::$app->user->id);
            return $this->setSuccessStatus();
        } catch (\DomainException $e) {
            return $this->setErrorStatus($e->getMessage());
        }
    }

    public function actionAddMembers($roomId)
    {
        $form = new RoomMembersForm();

        if ($form->load(\Yii::$app->request->getBodyParams()) && $form->validate()) {
            try {
                $this->service->addMembers($form, $roomId, \Yii::$app->user->id);
                return $this->setSuccessStatus();
            } catch (\DomainException $e) {
                return $this->setErrorStatus($e->getMessage());
            }
        }
        return $this->setErrorStatus('Выберите пользователей');
    }
}