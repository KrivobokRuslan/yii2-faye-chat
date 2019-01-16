<?php
/**
* @var \yii\web\View $this
*/
?>
<div class="panel panel-default">
    <div class="panel-body direct-chat-primary">
        <?php
            for ($i = 1; $i <=2; $i++) {
                echo $this->render('_dialog', ['i' => $i]);?>
            <?php }
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