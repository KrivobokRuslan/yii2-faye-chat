<?php
/**
 * @var \yii\web\View $this
 * @var array $users
 */

\krivobokruslan\fayechat\assets\ChatAssets::register($this);
?>
<div class="row">
    <div class="col-md-4">
        <ul id="user-container">
            <li id="user-template" data-user-id="" style="display: none;" class="user-row">
                <h5 class="username"></h5>
                <span class="user-status offline"></span>
                <span class="text-status">Offline</span>
            </li>
            <?php foreach ($users as $user) {
                echo $this->render('_user', [
                    'user' => $user
                ]);
            } ?>
        </ul>
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