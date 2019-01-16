<?php
/**
 * @var \yii\web\View $this
 * @var \krivobokruslan\fayechat\entities\DialogMessage $message
 * @var string $userId
 */

$bundle = $this->getAssetManager()->getBundle(\krivobokruslan\fayechat\assets\ChatAssets::class);

?>
<div class="direct-chat-msg<?php echo $message->isAuthor($userId) ? '' : ' right'?>">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name <?php echo $message->isAuthor($userId) ? ' pull-left' : ' pull-right'?>"><?php echo $message->author->getUsername();?></span>
        <span class="direct-chat-timestamp <?php echo $message->isAuthor($userId) ? ' pull-right' : ' pull-left'?>">23 Jan 2:00 pm</span>
    </div>
    <img class="direct-chat-img" src="<?php echo $message->author->getAvatar() ? : $bundle->baseUrl . '/img/no-avatar.png'; ?>">
    <div class="direct-chat-text">
        <?php echo $message->message; ?>
    </div>
</div>