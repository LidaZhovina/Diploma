<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Booking $model */

$this->title = 'Бронирование';
$this->registerCssFile('@web/css/form-booking.css');
?>

<div class="booking-page">
    <div class="booking-wrap">

        <!-- Шапка -->
        <div class="bk-header">
            <div class="bk-title">Бронирование</div>
            <div class="bk-sub"><?= Html::encode($room->roomType->name) ?> · <?= $room->price_per_day ?> ₽/ночь</div>
        </div>

        <!-- Прогресс -->
        <div class="bk-progress">
            <div class="bp active"></div>
            <div class="bp"></div>
            <div class="bp"></div>
            <div class="bp"></div>
        </div>
        <div class="bk-progress-labels">
            <span class="active-lbl">Детали</span>
            <span>Программы</span>
            <span>Гости</span>
            <span>Оплата</span>
        </div>

        <?php $form = ActiveForm::begin([
            'id'          => 'booking-step1',
            'fieldConfig' => [
                'template'      => "{label}\n{input}\n{error}",
                'labelOptions'  => ['class' => 'f-label'],
                'errorOptions'  => ['class' => 'invalid-feedback d-block'],
            ],
        ]); ?>

        <!-- Даты -->
        <div class="bk-section">
            <div class="bk-section-title">
                <div class="bk-icon"><i class="ti ti-calendar"></i></div> Даты проживания
            </div>
            <div class="f-row">
                <?= $form->field($model, 'arrival_date')
                    ->textInput(['type' => 'date', 'min' => date('Y-m-d', strtotime('+1 day'))]) ?>
                <?= $form->field($model, 'departure_date')
                    ->textInput(['type' => 'date', 'min' => date('Y-m-d', strtotime('+1 day'))]) ?>
            </div>
        </div>

        <!-- Контакт и гости -->
        <div class="bk-section">
            <div class="bk-section-title">
                <div class="bk-icon"><i class="ti ti-users"></i></div> Контакт и гости
            </div>
            <div class="f-row">
                <?= $form->field($model, 'contact_phone')
                    ->textInput(['placeholder' => '8 (___) ___-__-__']) ?>
                <?= $form->field($model, 'guests_count')
                    ->textInput(['type' => 'number', 'min' => 1]) ?>
            </div>
            <div class="f-group">
                <?= $form->field($model, 'comment')
                    ->textarea(['rows' => 3, 'placeholder' => 'Пожелания к заезду...']) ?>
            </div>
        </div>

        <div class="bk-total">
            <div>
                <div class="bk-total-lbl">Предоплата (30%)</div>
                <div class="bk-total-note">Остаток — при заезде</div>
            </div>
            <div class="bk-total-price" id="js-prepay">—</div>
        </div>

        <?= Html::submitButton('Далее →', ['class' => 'btn-booking-next']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$price = (int)$room->price_per_day;
$this->registerJs("
(function() {
    var a = document.getElementById('booking-arrival_date');
    var d = document.getElementById('booking-departure_date');
    var el = document.getElementById('js-prepay');
    function upd() {
        if (!a.value || !d.value) { el.textContent = '—'; return; }
        var diff = (new Date(d.value) - new Date(a.value)) / 86400000;
        if (diff <= 0) { el.textContent = '—'; return; }
        var total = diff * $price;
        var prepay = Math.round(total * 0.3);
        el.innerHTML = prepay.toLocaleString('ru') + ' ₽<small>' + total.toLocaleString('ru') + ' ₽ всего</small>';
    }
    a && a.addEventListener('change', upd);
    d && d.addEventListener('change', upd);
})();
");
?>