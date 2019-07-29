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
                $room = $this->service->create($form, \Yii::$app->user->id);
                return [
                    'roomInListTemplate' => $this->renderPartial('_room_in_list', [
                        'room' => $room
                    ])
                ];
            } catch (\DomainException $e) {
                return $this->setErrorStatus($e->getMessage());
            }
        }
    }

    public function actionView($roomId)
    {
        return $this->renderPartial('room', [
            'room' => $this->service->view($roomId, \Yii::$app->user->id),
            'userId' => \Yii::$app->user->id
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
}