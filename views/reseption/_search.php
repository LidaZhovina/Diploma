<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ReseptionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="booking-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'class' => 'd-flex align-items-end gap-3 flex-wrap',
        ],
    ]); ?>


    <div style="flex: 3;">
        <!-- <?= $form->field($model, 'id')->textInput(['placeholder' => 'ID бронирования']) ?> -->

        <?= $form->field($model, 'fullname')->textInput(['placeholder' => 'Фамилия, имя или полное ФИО'])->label(false) ?>
    </div>

    <?php // echo $form->field($model, 'price') 
    ?>

    <?php // echo $form->field($model, 'status_booking_id') 
    ?>

    <?php // echo $form->field($model, 'route_id') 
    ?>

    <?php // echo $form->field($model, 'amount_residents') 
    ?>

    <?php // echo $form->field($model, 'comment') 
    ?>

    <div style="flex: 1;">
        <div class="form-group" style="white-space: nowrap;">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Сбросить', 'index', ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>