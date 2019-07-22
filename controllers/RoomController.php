<?php

namespace krivobokruslan\fayechat\controllers;

use krivobokruslan\fayechat\forms\RoomForm;
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
        $form->addUserToMembers(\Yii::$app->user->id);
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
}