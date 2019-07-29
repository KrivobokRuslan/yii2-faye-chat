<?php
/**
 * @var \yii\web\View $this
 * @var \krivobokruslan\fayechat\entities\Room $room
 * @var string $userId
 */

?>

<ul class="nav nav-tabs" id="chatsTab" role="tablist">
    <li class="nav-item active">
        <a class="nav-link active" data-toggle="tab" role="tab" aria-controls="user-container" aria-selected="true" href="#room-content-<?php echo $room->id; ?>">Сообщения</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" role="tab" aria-controls="group-container" aria-selected="false" href="#room-members-<?php echo $room->id; ?>">Участники</a>
    </li>
</ul>
<div class="tab-content" id="chatsTabContent">
    <div role="tabpanel" aria-labelledby="room-message-tab" class="panel panel-default tab-pane fade active in" id="room-content-<?php echo $room->id; ?>">
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
    <div role="tabpanel" aria-labelledby="room-member-tab" class="panel panel-default tab-pane fade" id="room-members-<?php echo $room->id; ?>">
        <div class="panel-body direct-chat-primary">
            <ul class="list-group" style="list-style: none outside none">
                <?php
                if (!empty($room->members)) {
                    foreach ($room->members as $member) {
                        if ($member->id == $userId) continue;
                        echo $this->render('_member', [
                            'member' => $member,
                            'room' => $room
                        ]);
                    }
                } ?>
            </ul>
        </div>
    </div>
</div>