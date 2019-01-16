<?php

use Workerman\Worker;

$users = [];

$channel_server = new Channel\Server('0.0.0.0', 2206);

$ws_worker = new Worker("websocket://0.0.0.0:8000");

$ws_worker->onWorkerStart = function($worker) use (&$users)
{
    Channel\Client::connect('127.0.0.1', 2206);

    $inner_tcp_worker = new Worker("tcp://127.0.0.1:1234");

    $inner_tcp_worker->onMessage = function($connection, $data) use (&$users) {
        $data = json_decode($data);
        print_r($data);
        if (isset($users[$data->user])) {
            foreach ($users[$data->user] as $webconnection) {
                $webconnection->send($data->message);
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
        Channel\Client::publish('new-user', $connection->user);
    };
};

$ws_worker->onClose = function($connection) use(&$users)
{
    if (!isset($connection->user))  return;
    unset($users[$connection->user][$connection->id]);
};

Worker::runAll();