Yii2 faye chat
==============
Yii2 real time chat based on WebSocket (workerman)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist krivobokruslan/yii2-faye-chat "*"
```

or add

```
"krivobokruslan/yii2-faye-chat": "*"
```

to the require section of your `composer.json` file.


Configuration
-----
- Add configuration to your web config file
<pre>

'modules' => [
    ...
    'faye-chat' => [
        'class' => \krivobokruslan\fayechat\ChatModule::class,
        'tcp_host' => 'tcp://127.0.0.1:1234',
        'ws_host' => 'websocket://0.0.0.0:8000',
        'client_host' => 'ws://127.0.0.1:8000'
    ]
],
</pre>

- Add configuration to your console config file
<pre>

'controllerMap' => [
    ...
    'server' => \krivobokruslan\fayechat\console\controllers\ServerController::class
],

</pre>

- Add to your bootstrap section
<pre>
...
'bootstrap' => [..., \krivobokruslan\fayechat\bootstrap\Bootstrap::class],
...
</pre>

- Subscribe to events in your config file
<pre>
'on userCreate' => [\krivobokruslan\fayechat\ModuleMediator::class, 'onUserCreate'],
</pre>
or use your own event handler by example of 

<code>\krivobokruslan\fayechat\ModuleMediator::class</code>

- Your Identity class must implements <code>krivobokruslan\fayechat\interfaces\UserInterface</code>

- Run <code>./yii migrate --migrationPath=vendor\krivobokruslan\yii2-faye-chat\migrations</code>

Run server
-----

Run command ./yii server/start [string $ws_host] [string $tcp_host] </code>

Usage
-----
For automatically adding users to chat database trigger 

<code>krivobokruslan\fayechat\interfaces\UserCreateEventInterface</code> 

when your user was created

<code>Yii::$app->trigger('userCreate', new UserCreateEvent($user));</code>

Visit <code>/chat</code> url, your currently logged in user will automatically added to chat. All new users, if You subscribe and handle userCreate event will automatically added to chat and changes will be show to all connected users




