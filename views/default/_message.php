<?php
/**
 * @var \yii\web\View $this
 * @var int $i
 */
?>
<div class="direct-chat-msg<?php echo $i == 2 ? ' right' : ''?>">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name <?php echo $i == 2 ? ' pull-right' : ' pull-left'?>">Alexander Pierce</span>
        <span class="direct-chat-timestamp <?php echo $i == 2 ? ' pull-left' : ' pull-right'?>">23 Jan 2:00 pm</span>
    </div>
    <img class="direct-chat-img" src="<?php echo $bundle->baseUrl . '/img/no-avatar.png'; ?>">
    <div class="direct-chat-text">
        Is this template really for free? That's unbelievable!
    </div>
</div>