<?php

namespace krivobokruslan\fayechat\controllers;

use krivobokruslan\fayechat\entities\Room;
use krivobokruslan\fayechat\exceptions\NotFoundException;
use krivobokruslan\fayechat\forms\RoomMessageForm;
use krivobokruslan\fayechat\useCases\RoomMessageService;
use yii\base\Module;

class RoomMessageController extends MainController
{
    private $service;

    public function __construct($id, Module $module, array $config = [], RoomMessageService $service)
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionCreate($roomId)
    {
        try {
            $room = $this->findRoomModel($roomId);
        } catch (NotFoundException $e) {
            return $this->setErrorStatus($e->getMessage());
        }

        $form = new RoomMessageForm($room);

        if ($form->load(\Yii::$app->request->getBodyParams(), '') && $form->validate()) {
            try {
                $message = $this->service->create($form, $room->id, \Yii::$app->user->id);
                $message->refresh();
                return $this->renderPartial('_message', [
                    'message' => $message,
                    'userId' => \Yii::$app->user->id
                ]);
            } catch (\DomainException $e) {
                return $this->setErrorStatus($e->getMessage());
            }
        }
    }

    public function actionDeleteMe($id)
    {
        try {
            $this->service->deleteMe($id, \Yii::$app->user->id);
            return $this->setResponseSuccessStatus();
        } catch (\DomainException $e) {
            return $this->setResponseErrorStatus($e->getMessage());
        }
    }

    public function actionDelete($id)
    {
        try {
            $message = $this->service->delete($id, \Yii::$app->user->id);
            \Yii::$app->faye->send($message->id, '/room/' . $message->room->id . '/message/delete');
            return $this->setResponseSuccessStatus();
        } catch (\DomainException $e) {
            return $this->setResponseErrorStatus($e->getMessage());
        }
    }

    private function findRoomModel($id): ?Room
    {
        $room = Room::findOne(['id' => $id]);
        if ($room !== null) {
            return $room;
        }
        throw new NotFoundException();
    }
}