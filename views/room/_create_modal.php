<?php
/**
 * @var \yii\web\View $this
 * @var \krivobokruslan\fayechat\forms\RoomForm $model
 */
?>
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#createRoomModal">Добавить группу</button>
<div class="modal fade" id="createRoomModal" tabindex="-1" role="dialog" aria-labelledby="createRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить группу</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php $form = \yii\widgets\ActiveForm::begin(); ?>
                    <?php echo $form->field($model, 'title')->textInput();?>
                    <button type="button" class="btn btn-success">Создать</button>
                    <?php \yii\widgets\ActiveForm::end();?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary">Создать</button>
            </div>
        </div>
    </div>
</div>