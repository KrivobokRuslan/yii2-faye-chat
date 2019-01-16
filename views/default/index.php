<?php
/**
 * @var \yii\web\View $this
 * @var array $users
 */

\krivobokruslan\fayechat\assets\ChatAssets::register($this);
?>
    <div class="row">
        <div class="col-md-4">
            <ul id="user-container" class="list-group">
                <li id="user-template" data-user-id="" style="display: none;" class="user-row list-group-item">
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?php echo $bundle->baseUrl . '/img/no-avatar.png'; ?>" width="50px"
                                 height="50px">
                        </div>
                        <div class="pull-left info">
                            <p class="username"></p>
                            <span class="user-status offline"></span>
                            <span class="text-status">Offline</span>
                        </div>
                    </div>
                </li>
                <?php foreach ($users as $user) {
                    echo $this->render('_user', [
                        'user' => $user
                    ]);
                } ?>
            </ul>
        </div>
        <div class="col-md-6" id="dialog-container">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="direct-chat-msg">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">Alexander Pierce</span>
                            <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
                        </div>
                        <img class="direct-chat-img" src="<?php echo $bundle->baseUrl . '/img/no-avatar.png'; ?>">
                        <div class="direct-chat-text">
                            Is this template really for free? That's unbelievable!
                        </div>
                    </div>
                    <div class="direct-chat-msg right">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">Alexander Pierce</span>
                            <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
                        </div>
                        <img class="direct-chat-img" src="<?php echo $bundle->baseUrl . '/img/no-avatar.png'; ?>">
                        <div class="direct-chat-text">
                            Is this template really for free? That's unbelievable!
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Message...">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$this->registerJsFile('http://localhost:8000/client.js');
$this->registerJs("
    var client = new Faye.Client('http://localhost:8000?user_id=" . Yii::$app->user->id . "');

    client.subscribe('/connect', function(data) {
        $('#user-' + data.user_id + ' .user-status').removeClass('offline').addClass('online');
        $('#user-' + data.user_id + ' .text-status').text('Online');
    });
    client.subscribe('/get-online-users/" . Yii::$app->user->id . "', function(data) {
        for (user_id in data.users) {
            $('#user-' + data.users[user_id].user_id + ' .user-status').removeClass('offline').addClass('online');
            $('#user-' + data.users[user_id].user_id + ' .text-status').text('Online');
        }
    });
    client.subscribe('/new-user', function(data) {
        var template = $('#user-container #user-template').clone().prop('id', 'user-' + data.id).attr('data-user-id', data.id);
        template.find('h5').text(data.username);
        $('#user-container').append(template);
        template.show();
    });
    client.subscribe('/disconnect', function(data) {
        $('#user-' + data.user_id + ' .user-status').removeClass('inline').addClass('offline');
        $('#user-' + data.user_id + ' .text-status').text('Offline');
    });
", \yii\web\View::POS_END);