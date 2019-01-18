<?php

namespace krivobokruslan\fayechat\console\controllers;

use krivobokruslan\fayechat\server\messages\UserConnectMessage;
use krivobokruslan\fayechat\server\messages\UsersOnlineMessage;
use Workerman\Worker;
use yii\console\Controller;

class ServerController extends Controller
{

    public function actionStart($ws_host = "websocket://0.0.0.0:8000", $tcp_host = "tcp://127.0.0.1:1234")
    {
        $users = [];
        $ws_worker = new Worker($ws_host);
        $ws_worker->onWorkerStart = function($worker) use (&$users, $tcp_host)
        {
            $inner_tcp_worker = new Worker($tcp_host);

            $inner_tcp_worker->onMessage = function($connection, $data) use (&$users) {
                $data = json_decode($data);
                if (property_exists($data, 'userId') && isset($users[$data->userId])) {
                    foreach ($users[$data->userId] as $webconnection) {
                        $webconnection->send(json_encode($data));
                    }
                } else {
                    foreach ($users as $webconnections) {
                        foreach ($webconnections as $webconnection) {
                            $webconnection->send(json_encode($data));
                        }
                    }
                }
            };
            $inner_tcp_worker->listen();
        };

        $ws_worker->onConnect = function($connection) use (&$users)
        {
            $connection->onWebSocketConnect = function($connection) use (&$users)
            {
                $users[$_GET['user_id']][$connection->id] = $connection;
                $connection->user = $_GET['user_id'];
                foreach ($users as $webconnections) {
                    foreach ($webconnections as $webconnection) {
                        $webconnection->send(UserConnectMessage::createAsJson('userConnect', $connection->user));
                    }
                }
                $connection->send(UsersOnlineMessage::createAsJson('usersOnline', array_keys($users)));
                print_r(array_keys($users));
            };
        };

        $ws_worker->onClose = function($connection) use(&$users)
        {
            if (!isset($connection->user))  return;
            unset($users[$connection->user][$connection->id]);
            if (empty($users[$connection->user])) {
                unset($users[$connection->user]);
                foreach ($users as $webconnections) {
                    foreach ($webconnections as $webconnection) {
                        $webconnection->send(UserConnectMessage::createAsJson('userDisconnect', $connection->user));
                    }
                }
            }
            print_r(array_keys($users));
        };

        Worker::runAll();
    }

    public function actionStop()
    {

    }
}