<?php
/**
* @var \yii\web\View $this
* @var \krivobokruslan\fayechat\entities\Dialog $dialog
* @var string $userId
*/
?>
<div class="panel panel-default">
    <div class="panel-body direct-chat-primary">
        <?php
        if (!empty($dialog->messages)) {
            foreach ($dialog->messages as $message) {
                echo $this->render('_message', [
                    'message' => $message,
                    'currentUserId' => $userId
                ]);
            }
        } else {
            echo 'There is no messages yet...';
        }
           ?>
    </div>
    <div class="panel-footer">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Message...">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button">Send</button>
            </span>
        </div>
    </div>
</div>