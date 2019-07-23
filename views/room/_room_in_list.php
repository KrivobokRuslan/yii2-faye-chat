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
            <p><span class="username"><?php echo $room->title ?></span></p>
            <span class="text-status">Участников: <span class="members-count"><?php echo $room->getCountMembers(); ?></span></span>
        </div>
    </div>
</li>