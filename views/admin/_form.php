<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Booking $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="booking-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'room_id')->textInput() ?>

    <?= $form->field($model, 'arrival_date')->textInput() ?>

    <?= $form->field($model, 'departure_date')->textInput() ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_booking_id')->textInput() ?>

    <?= $form->field($model, 'wellness_program_id')->textInput() ?>

    <?= $form->field($model, 'amount_residents')->textInput() ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
