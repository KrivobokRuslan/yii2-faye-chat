<?php


namespace krivobokruslan\fayechat\controllers;

use yii\web\Controller;
use yii\web\Response;

class MainController extends Controller
{
    public function setErrorStatus(string $message)
    {
        $this->setResponseJsonFormat();
        return [
            'status' => false,
            'error' => $message
        ];
    }

    public function setResponseJsonFormat()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }
}