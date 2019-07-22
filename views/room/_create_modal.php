<?php
use kartik\select2\Select2;
/**
 * @var \yii\web\View $this
 * @var array $users
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
                    <div class="col-md-12">
                        <?php $form = \yii\widgets\ActiveForm::begin([
                            'id' => 'roomCreateForm'
                        ]); ?>
                        <?php echo $form->field($model, 'title')->textInput();?>
                        <?php echo $form->field($model, 'members')->widget(Select2::class, [
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