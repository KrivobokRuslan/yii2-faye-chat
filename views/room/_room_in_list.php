<?php
/**
 * @var \yii\web\View $this
 * @var \krivobokruslan\fayechat\entities\Room $room
 */

$bundle = $this->getAssetManager()->getBundle(\krivobokruslan\fayechat\assets\ChatAssets::class);

?>

<li id="room-<?php echo $room->id; ?>" data-room-id="<?php echo $room->id; ?>" class="group-row">
    <div class="group-panel">
        <div class="pull-left image">
            <img src="<?php echo $bundle->baseUrl . '/img/group.png'; ?>" width="50px" height="50px">
        </div>
        <div class="pull-left info">
            <p><span class="username"><?php echo $room->title ?></span><span class="income-message" id="income-message-room-<?php echo $room->id; ?>"></span></p>
            <span class="text-status">Участников: <span class="members-count"><?php echo $room->getCountMembers(); ?></span></span>
        </div>
        <?php if($room->isOwner(Yii::$app->user->id)): ?>
            <div class="pull-right room-delete" data-room-id="<?php echo $room->id;?>" style="font-size: 25px; margin-top: 10px; margin-right: 5px;">
                <span class="glyphicon glyphicon-trash"></span>
            </div>
        <?php else : ?>
            <div class="pull-right room-leave" data-room-id="<?php echo $room->id;?>" style="font-size: 25px; margin-top: 10px; margin-right: 5px;">
                <span class="glyphicon glyphicon-log-out"></span>
            </div>
        <?php endif; ?>
    </div>
</li>