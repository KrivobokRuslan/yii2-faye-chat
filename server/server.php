<?php

use Workerman\Worker;
use krivobokruslan\fayechat\server\messages\UserConnectMessage;
use krivobokruslan\fayechat\server\messages\UsersOnlineMessage;

require_once __DIR__ . '/../../../autoload.php';

$users = [];

$ws_worker = new Worker("websocket://0.0.0.0:8000");

$ws_worker->onWorkerStart = function($worker) use (&$users)
{
    $inner_tcp_worker = new Worker("tcp://127.0.0.1:1234");

    $inner_tcp_worker->onMessage = function($connection, $data) use (&$users) {
        $data = json_decode($data);
        if (property_exists($data, 'user') && isset($users[$data->user])) {
            foreach ($users[$data->user] as $webconnection) {
                $webconnection->send($data);
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
                $webconnection->send(UserConnectMessage::createAsJson('/user-connect', $connection->user));
            }
        }
        $connection->send(UsersOnlineMessage::createAsJson('/users-online', array_keys($users)));
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
                $webconnection->send(UserConnectMessage::createAsJson('/user-disconnect', $connection->user));
            }
        }
    }
};

Worker::runAll();