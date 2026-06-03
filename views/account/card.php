<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var app\models\Booking $booking */
/** @var app\models\CardPaymentForm $model */

$confirmUrl = \yii\helpers\Url::to(['account/confirm-payment', 'id' => $booking->id], true);
$checkUrl   = \yii\helpers\Url::to(['account/check-payment-status', 'id' => $booking->id]);

$this->registerCssFile('@web/css/cardPayment.css');
$this->registerCssFile('@web/css/form-booking.css');
$this->registerJsFile('/js/payment.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="booking-page">
    <div class="booking-wrap">
        <!-- Прогресс -->
        <div class="bk-progress">
            <div class="bp done"></div>
            <div class="bp done"></div>
            <div class="bp done"></div>
            <div class="bp active"></div>
        </div>
        <div class="bk-progress-labels">
            <span>Детали</span>
            <span>Программы</span>
            <span>Гости</span>
            <span class="active-lbl">Оплата</span>
        </div>

        <div class="bk-header">
            <div class="bk-title">Оплата по карте</div>
            <div class="bk-sub">Заказ № <?= $booking->id ?></div>
        </div>

        <!-- Сумма -->
        <div class="bk-section">
            <div class="bk-section-title">
                <div class="bk-icon"><i class="ti ti-receipt"></i></div> Детали платежа
            </div>
            <div class="booking-summary-row">
                <span class="lbl">Сумма предоплаты (30%)</span>
                <span class="val" style="color:#3B4593;font-size:18px;font-weight:800">
                    <?= $booking->payment_amount ?> ₽
                </span>
            </div>
            <div class="booking-summary-row" style="margin-top:4px">
                <span class="lbl">Остаток</span>
                <span class="val">Оплатите при заезде</span>
            </div>
        </div>

        <!-- Виртуальная карта -->
        <div class="card-preview mb-3">
            <div class="card-number-preview">
                <span id="card-preview-number">•••• •••• •••• ••••</span>
            </div>
            <div class="card-details-row d-flex justify-content-center">
                <div class="card-detail-item">
                    <span class="card-label">ДЕЙСТВУЕТ ДО</span>
                    <span class="card-value" id="card-preview-expiry">••/••</span>
                </div>
                <div class="card-detail-item">
                    <span class="card-label">CVV</span>
                    <span class="card-value" id="card-preview-cvv">•••</span>
                </div>
            </div>
            <div class="card-footer-preview d-flex justify-content-between align-items-end">
                <div class="card-holder-preview">
                    <span class="card-label">ВЛАДЕЛЕЦ</span>
                    <div class="card-value" id="card-preview-holder">IVAN IVANOV</div>
                </div>
                <div class="card-logo">
                    <img src="https://img.icons8.com/color/48/000000/mir"
                        class="card-logo-img" alt="Mir">
                </div>
            </div>
        </div>

        <!-- Форма -->
        <div class="bk-section">
            <div class="bk-section-title">
                <div class="bk-icon"><i class="ti ti-credit-card"></i></div> Данные карты
            </div>

            <?php $form = ActiveForm::begin([
                'id'          => 'card-payment-form',
                'method'      => 'post',
                'fieldConfig' => [
                    'template'     => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'f-label'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <?= $form->field($model, 'card_number')
                ->label('Номер карты')
                ->widget(MaskedInput::class, [
                    'mask'    => '9999 9999 9999 9999',
                    'options' => ['placeholder' => '1234 5678 9012 3456'],
                ]) ?>

            <div class="f-row">
                <?= $form->field($model, 'expiry')
                    ->label('Срок действия')
                    ->widget(MaskedInput::class, [
                        'mask'    => '99/99',
                        'options' => ['placeholder' => 'ММ/ГГ'],
                    ]) ?>
                <?= $form->field($model, 'cvv')
                    ->label('CVV')
                    ->passwordInput(['placeholder' => '•••', 'maxlength' => 3]) ?>
            </div>

            <?= $form->field($model, 'card_holder')
                ->label('Владелец карты')
                ->textInput(['id' => 'card-holder', 'placeholder' => 'IVAN IVANOV']) ?>

            <div style="height:8px"></div>
            <?= Html::submitButton(
                'Оплатить ' . $booking->payment_amount . ' ₽',
                ['class' => 'btn-booking-next']
            ) ?>

            <?php ActiveForm::end(); ?>
        </div>

        <?= Html::a('← Назад', ['account/index'], ['class' => 'btn-booking-back', 'style' => 'margin-top:8px']) ?>

    </div>
</div>