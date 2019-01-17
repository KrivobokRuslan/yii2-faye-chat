<?php

namespace krivobokruslan\fayechat\console\controllers;

use krivobokruslan\fayechat\server\messages\UserConnectMessage;
use krivobokruslan\fayechat\server\messages\UsersOnlineMessage;
use Workerman\Worker;
use yii\console\Controller;

class ServerController extends Controller
{

    private $users = [];

    public function actionStart($ws_host = "websocket://0.0.0.0:8000", $tcp_host = "tcp://127.0.0.1:1234")
    {
        $ws_worker = new Worker($ws_host);
        $ws_worker->onWorkerStart = function($worker) use ($tcp_host)
        {
            $inner_tcp_worker = new Worker($tcp_host);

            $inner_tcp_worker->onMessage = function($connection, $data) {
                $data = json_decode($data);
                if (property_exists($data, 'userId') && isset($this->users[$data->userId])) {
                    foreach ($this->users[$data->userId] as $webconnection) {
                        $webconnection->send($data);
                    }
                } else {
                    foreach ($this->users as $webconnections) {
                        foreach ($webconnections as $webconnection) {
                            $webconnection->send(json_encode($data));
                        }
                    }
                }
            };
            $inner_tcp_worker->listen();
        };

        $ws_worker->onConnect = function($connection)
        {
            $connection->onWebSocketConnect = function($connection)
            {
                $this->users[$_GET['user_id']][$connection->id] = $connection;
                $connection->user = $_GET['user_id'];
                foreach ($this->users as $webconnections) {
                    foreach ($webconnections as $webconnection) {
                        $webconnection->send(UserConnectMessage::createAsJson('userConnect', $connection->user));
                    }
                }
                $connection->send(UsersOnlineMessage::createAsJson('usersOnline', array_keys($this->users)));
            };
        };

        $ws_worker->onClose = function($connection)
        {
            if (!isset($connection->user))  return;
            unset($this->users[$connection->user][$connection->id]);
            if (empty($this->users[$connection->user])) {
                unset($this->users[$connection->user]);
                foreach ($this->users as $webconnections) {
                    foreach ($webconnections as $webconnection) {
                        $webconnection->send(UserConnectMessage::createAsJson('userDisconnect', $connection->user));
                    }
                }
            }
        };

        Worker::runAll();
    }

    public function actionStop()
    {

    }
}