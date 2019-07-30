<?php
use kartik\select2\Select2;
/**
 * @var \yii\web\View $this
 * @var array $users
 * @var \krivobokruslan\fayechat\forms\RoomMembersForm $model
 * @var string $roomId
 */
?>
<span class="glyphicon glyphicon-plus" data-toggle="modal" data-target="#addRoomMembersModal"></span>
<div class="modal fade" id="addRoomMembersModal" tabindex="-1" role="dialog" aria-labelledby="addRoomMembersModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить участников</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php $form = \yii\widgets\ActiveForm::begin([
                            'id' => 'roomMembersForm',
                            'options' => [
                                'data-room-id' => $roomId
                            ]
                        ]); ?>
                        <?php echo $form->field($model, 'members')->widget(\kartik\select2\Select2::class, [
                            'data' => \yii\helpers\ArrayHelper::map($users, 'id', 'username'),
                            'options' => [
                                'placeholder' => 'Выберите пользователя',
                                'multiple' => true
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                        <button type="submit" class="btn btn-success">Создать</button>
                        <?php \yii\widgets\ActiveForm::end();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>