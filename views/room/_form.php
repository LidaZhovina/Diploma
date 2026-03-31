<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Room $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="room-form w-50">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'room_type_id')->dropDownList($types, ['prompt' => 'Выберите тип номера']) ?>

    <?= $form->field($model, 'price_per_day')->textInput() ?>

    <?= $form->field($model, 'number_guests')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'number')->textInput() ?>

    <?= $form->field($model, 'floor')->textInput() ?>

    <!-- Поле загрузки нескольких изображений -->
    <div class="form-group">
        <label class="control-label">Изображения номера</label>
        <input type="file" name="imageFiles[]" multiple accept="image/*" class="form-control">
        <div class="help-block">Можно выбрать несколько файлов (jpg, jpeg, png, gif, webp).</div>
    </div>

    <!-- Отображение текущих изображений при редактировании -->
    <?php if (!$model->isNewRecord && $model->roomImages): ?>
        <div class="form-group mt-3">
            <label>Текущие изображения</label>
            <div class="row">
                <?php foreach ($model->roomImages as $image): ?>
                    <div class="col-md-3 mb-2">
                        <img src="<?= Url::to(['room/display-image', 'id' => $image->id]) ?>" class="img-thumbnail" style="max-width:150px;">
                        <?= Html::a('Удалить', ['room/delete-image', 'id' => $image->id], [
                            'class' => 'btn btn-danger btn-sm mt-2',
                            'data' => ['confirm' => 'Удалить изображение?', 'method' => 'post']
                        ]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group d-flex justify-content-between">
        <?= Html::submitButton('Создать', ['class' => 'btn register']) ?>
        <?= Html::a('Назад', ['index', 'id' => $model->id], ['class' => 'btn register']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>