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
            <ul id="user-container" class="list-group">
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
        </div>
        <div class="col-md-6" id="dialog-container">

        </div>
    </div>

<?php

$this->registerJs("
    var wshost = '".$clientHost."/?user_id=" . Yii::$app->user->id . "';
    var chat_module_bundle = '".$bundle->baseUrl."';
", \yii\web\View::POS_BEGIN);