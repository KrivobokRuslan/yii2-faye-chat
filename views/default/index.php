<?php
/**
 * @var \yii\web\View $this
 * @var array $users
 * @var array $rooms
 * @var \krivobokruslan\fayechat\forms\RoomForm $roomForm
 * @var string $clientHost
 */

\krivobokruslan\fayechat\assets\ChatAssets::register($this);
$bundle = $this->getAssetManager()->getBundle(\krivobokruslan\fayechat\assets\ChatAssets::class);
?>
    <div class="row">
        <div class="col-md-4">
            <ul class="nav nav-tabs" id="chatsTab" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" data-toggle="tab" role="tab" aria-controls="user-container" aria-selected="true" href="#user-container">Диалоги</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" role="tab" aria-controls="group-container" aria-selected="false" href="#group-container">Группы</a>
                </li>
            </ul>
            <div class="tab-content" id="chatsTabContent">
                <ul role="tabpanel" aria-labelledby="user-container-tab" id="user-container" class="list-group tab-pane fade active in">
                    <li id="user-template" data-user-id="" style="display: none;" class="user-row">
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
                <ul role="tabpanel" aria-labelledby="group-container-tab" id="group-container" class="list-group tab-pane fade">
                    <li>
                        <?php echo $this->render('../room/_create_modal', [
                            'model' => $roomForm,
                            'users' => $users
                        ]); ?>
                    </li>
                    <li id="group-template" data-room-id="" style="display: none;" class="group-row">
                        <div class="group-panel">
                            <div class="pull-left image">
                                <img src="<?php echo $bundle->baseUrl . '/img/group.png'; ?>" width="50px" height="50px">
                            </div>
                            <div class="pull-left info">
                                <p><span class="username"></span></p>
                                <span class="text-status">Участников: <span class="members-count"></span></span>
                            </div>
                            <div class="pull-right room-leave" data-room-id="" style="font-size: 25px; margin-top: 10px; margin-right: 5px;">
                                <span class="glyphicon glyphicon-log-out"></span>
                            </div>
                        </div>
                    </li>
                    <?php foreach ($rooms as $room) {
                        echo $this->render('../room/_room_in_list', [
                            'room' => $room
                        ]);
                    } ?>
                </ul>
            </div>
        </div>
        <div class="col-md-6" id="dialog-container">

        </div>
    </div>
    <audio id="chat-audio-norif" src="<?php echo $bundle->baseUrl . '/notif.mp3'?>"></audio>
<?php

$this->registerJs("
    var currentUser = ".Yii::$app->user->id.";
    var wshost = '".$clientHost."/?user_id=" . Yii::$app->user->id . "';
    var chat_module_bundle = '".$bundle->baseUrl."';
", \yii\web\View::POS_BEGIN);