<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Route $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="route-form w-50">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'length')->textInput() ?>

    <?= $form->field($model, 'date_start')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'time_start')->textInput() ?>

    <?= $form->field($model, 'duration')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'outfit')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'number_participant')->textInput() ?>

    <?= $form->field($model, 'level_id')->dropDownList($levels, ['prompt' => '---']) ?>

    <div class="form-group">
        <label class="control-label">Изображение маршрута</label>
        <input type="file" name="imageFile" accept="image/*" class="form-control">
        <?php if (!$model->isNewRecord && $model->WellnessImage): ?>
            <div class="mt-2">
                <p>Текущее изображение:</p>
                <img src="<?= $model->imageUrl ?>" style="max-width: 200px; max-height: 200px;">
            </div>
        <?php endif; ?>
    </div>


    <div class="form-group d-flex justify-content-between">
        <?= Html::submitButton('Сохранить', ['class' => 'btn register']) ?>
        <?= Html::a('Назад', ['index', 'id' => $model->id], ['class' => 'btn register']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>