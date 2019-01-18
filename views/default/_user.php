<?php
/**
 * @var \yii\web\View $this
 * @var \krivobokruslan\fayechat\interfaces\UserInterface $user
 */

$bundle = $this->getAssetManager()->getBundle(\krivobokruslan\fayechat\assets\ChatAssets::class);

?>

<li id="user-<?php echo $user->getChatUserId(); ?>" data-user-id="<?php echo $user->getChatUserId(); ?>" class="user-row">
    <div class="user-panel">
        <div class="pull-left image">
            <img src="<?php echo $user->getChatAvatar() ? : $bundle->baseUrl . '/img/no-avatar.png'; ?>" width="50px" height="50px">
        </div>
        <div class="pull-left info">
            <p><span class="username"><?php echo $user->getChatUsername(); ?></span> <span class="income-message" id="income-message-user-<?php echo $user->getChatUserId(); ?>"></span> </p>
            <span class="user-status offline"></span>
            <span class="text-status">Offline</span>
        </div>
    </div>
</li>
