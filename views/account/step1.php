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
        <!-- <?= Html::submitButton('Далее', ['class' => 'btn register']) ?> -->
        <!-- Кнопка-триггер модального окна -->
        <?= Html::submitButton('Далее', ['class' => 'btn register', 'data-bs-toggle'=>"modal", 'data-bs-target'=>"#exampleModal"]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- Модальное окно для обработки персональных данных -->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Хотите выбрать оздоровительную программу?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="window.location.href='<?= Url::to(['account/select-program']) ?>'">Да</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= Url::to(['account/guests-data']) ?>'">Нет</button>
            </div>
        </div>
    </div>
</div> -->