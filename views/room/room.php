<?php
/**
 * @var \yii\web\View $this
 * @var \krivobokruslan\fayechat\entities\Room $room
 * @var string $userId
 */
?>
<div class="panel panel-default" id="room-content-<?php echo $room->id; ?>">
    <div class="panel-body direct-chat-primary" id="message-container">
        <?php
        if (!empty($room->messages)) {
            foreach ($room->messages as $message) {
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
            <input type="text" class="form-control" placeholder="Message..." id="new-message">
            <input type="hidden" id="room-id" value="<?php echo $room->id;?>">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button" id="room-send-new-message">Send</button>
            </span>
        </div>
    </div>
</div>