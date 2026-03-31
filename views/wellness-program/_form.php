<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\WellnessProgram $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="wellness-program-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'duration')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
    <label class="control-label">Изображение программы</label>
    <input type="file" name="imageFile" accept="image/*" class="form-control">
    <?php if (!$model->isNewRecord && $model->wellnessImage): ?>
        <div class="mt-2">
            <p>Текущее изображение:</p>
            <?= Html::img($model->imageUrl, ['style' => 'max-width:200px;']) ?>
        </div>
    <?php endif; ?>
</div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
