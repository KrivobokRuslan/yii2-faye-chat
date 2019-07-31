<?php
/**
* @var \yii\web\View $this
* @var \krivobokruslan\fayechat\entities\Dialog $dialog
* @var string $userId
*/
?>
<div class="panel panel-default" id="dialog-<?php echo $dialog->id; ?>">
    <div class="panel-body direct-chat-primary" id="message-container">
        <?php
        if (!empty($dialog->lastMessages)) {
            foreach (array_reverse($dialog->lastMessages) as $message) {
                echo $this->render('_message', [
                    'message' => $message,
                    'userId' => $userId
                ]);
            }
        } else {
            echo 'There is no messages yet...';
        }
           ?>
    </div>
    <div class="panel-footer">
        <div class="input-group">
            <input type="text" class="form-control dialog-message" placeholder="Message..." id="new-message">
            <input type="hidden" id="dialog-id" value="<?php echo $dialog->id;?>">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button" id="send-new-message">Send</button>
            </span>
        </div>
    </div>
</div>