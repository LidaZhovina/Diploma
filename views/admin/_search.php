<?php

use app\models\RoomType;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AdminSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="booking-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>


    <?= $form->field($model, 'room_id')->dropDownList(RoomType::getTypes(), ['prompt' => '---']) ?>

    <?= $form->field($model, 'arrival_date')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'departure_date')->textInput(['type' => 'date']) ?>


    <?php // echo $form->field($model, 'status_booking_id') ?>

    <?php // echo $form->field($model, 'wellness_program_id') ?>

    <?php // echo $form->field($model, 'route_id') ?>

    <?php // echo $form->field($model, 'amount_residents') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбросить',['index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
