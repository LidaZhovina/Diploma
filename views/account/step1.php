<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Booking $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="booking-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'arrival_date')->textInput(['type' => 'date', 'min' => date('Y-m-d', strtotime('+1 day'))]) ?>

    <?= $form->field($model, 'departure_date')->textInput(['type' => 'date', 'min' => date('Y-m-d', strtotime('+1 day'))]) ?>

    <?= $form->field($model, 'contact_phone')->textInput() ?>

    <?= $form->field($model, 'guests_count')->textInput() ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Далее', ['class' => 'btn register', 'data-bs-toggle'=>"modal", 'data-bs-target'=>"#exampleModal"]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>