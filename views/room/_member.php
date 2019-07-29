<?php
/**
 * @var \yii\web\View $this
 * @var \krivobokruslan\fayechat\entities\User $member
 * @var string $roomId
 */

$bundle = $this->getAssetManager()->getBundle(\krivobokruslan\fayechat\assets\ChatAssets::class);
?>

<li class="user-row" id="user-in-room-<?php echo $member->getChatUserId(); ?>" data-user-id="<?php echo $member->getChatUserId(); ?>">
    <div class="user-panel">
        <div class="pull-left image">
            <img src="<?php echo $member->getChatAvatar() ? : $bundle->baseUrl . '/img/no-avatar.png'; ?>" width="50px" height="50px">
        </div>
        <div class="pull-left info">
            <p><span class="username"><?php echo $member->getChatUsername(); ?></span></p>
            <span class="user-status offline"></span>
            <span class="text-status">Offline</span>
        </div>
    </div>
    <div
        class="room-member-remove btn btn-danger"
        style="float: right;"
        data-user-id="<?php echo $member->getChatUserId(); ?>"
        data-room-id="<?php echo $roomId; ?>"
    >
        <i class="glyphicon glyphicon-ban-circle"></i>
    </div>
</li>
