<?php
/**
 * @var \yii\web\View $this
 * @var array $users
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
                <ul role="tabpanel" aria-labelledby="user-container-tab" id="user-container" class="list-group tab-pane fade show active in">
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
                <ul role="tabpanel" aria-labelledby="group-container-tab" id="group-container" class="tab-pane fade">
                    <li>
                        Room
                    </li>
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