<?php
/**
 * @var \yii\web\View $this
 * @var \krivobokruslan\fayechat\entities\Room $room
 */

$bundle = $this->getAssetManager()->getBundle(\krivobokruslan\fayechat\assets\ChatAssets::class);

?>

<li id="room-<?php echo $room->id; ?>" data-user-id="<?php echo $room->id; ?>" class="user-row">
    <div class="user-panel">
        <div class="pull-left image">
            <img src="<?php echo $bundle->baseUrl . '/img/group.png'; ?>" width="50px" height="50px">
        </div>
        <div class="pull-left info">
            <p><span class="username"><?php echo $room->title ?></span> <span class="income-message" id="income-message-user-<?php echo $room->id; ?>"></span> </p>
            <span class="text-status">Участников: <?php echo $room->getCountMembers(); ?></span>
        </div>
    </div>
</li>