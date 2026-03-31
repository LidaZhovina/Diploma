<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Booking $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="booking-form ">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'arrival_date')->textInput(['type' => 'date', 'min' => date('Y-m-d', strtotime('+1 day'))]) ?>

    <?= $form->field($model, 'departure_date')->textInput(['type' => 'date', 'min' => date('Y-m-d', strtotime('+1 day'))]) ?>

    <!-- <?= $form->field($model, 'wellness_program_id')->textInput() ?>

    <?= $form->field($model, 'route_id')->textInput() ?> -->

    <?= $form->field($model, 'amount_residents')->textInput() ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'agreement', [
        'template' => "{input}\n{label}\n{error}",
        'options' => ['class' => 'form-group'],
    ])->checkbox([
        'label' => false,
    ])->label(
        'Я даю <a href="#" class="modal-link" data-modal="modal-personal">согласие на обработку персональных данных</a> и подтверждаю ознакомление с <a href="#" class="modal-link" data-modal="modal-user-agreement">пользовательским соглашением</a> и <a href="#" class="modal-link" data-modal="modal-privacy">политикой конфиденциальности</a>.'
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Далее', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- Модальное окно для обработки персональных данных -->