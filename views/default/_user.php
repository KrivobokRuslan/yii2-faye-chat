<?php
/**
 * @var \yii\web\View $this
 * @var \krivobokruslan\fayechat\interfaces\UserInterface $user
 */

$bundle = $this->getAssetManager()->getBundle(\krivobokruslan\fayechat\assets\ChatAssets::class);

?>

<li id="user-<?php echo $user->getId(); ?>" data-user-id="<?php echo $user->getId(); ?>">
    <h5 class="username"><img src="<?php echo $user->getAvatar() ? : $bundle->baseUrl . '/img/no-avatar.png'; ?>" width="50px" height="50px"><?php echo $user->getUsername(); ?></h5>
    <span class="user-status offline"></span>
    <span class="text-status">Offline</span>
</li>
