<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var app\models\Booking $booking */
/** @var app\models\CardPaymentForm $model */
/** @var yii\widgets\ActiveForm $form */
$confirmUrl = \yii\helpers\Url::to(['account/confirm-payment', 'id' => $booking->id], true);
$checkUrl = \yii\helpers\Url::to(['account/check-payment-status', 'id' => $booking->id]);

$this->registerCssFile('@web/css/cardPayment.css');
$this->registerJsFile('/js/payment.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="container payment-page-container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6    ">
            <div class="card payment-card">
                <div class="card-header text-center payment-card-header">
                    <h2>Оплата заказа № <?= $booking->id ?></h2>
                </div>
                <div class="card-body payment-card-body">

                    <div class="payment-amount-block">
                        <strong>Сумма предоплаты (30%):</strong>
                        <?= $booking->payment_amount ?> ₽
                        <span class="payment-amount-note">(Остаток оплатите при заезде.)</span>
                    </div>

                    <!-- ========== ВИРТУАЛЬНАЯ КАРТА ========== -->
                    <div class="card-preview mb-4">
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
                                <img src="https://img.icons8.com/color/48/000000/mir" class="card-logo-img" alt="Mir">
                            </div>
                        </div>
                    </div>

                    <!-- ========== ФОРМА ОПЛАТЫ ========== -->
                    <div class="card-form">

                        <?php $form = ActiveForm::begin(['id' => 'card-payment-form', 'method' => 'post']); ?>

                        <?= $form->field($model, 'card_number')->widget(MaskedInput::class, [
                            'mask' => '9999 9999 9999 9999',
                            'options' => ['placeholder' => '1234 5678 9012 3456']
                        ]) ?>

                        <div class="row">
                            <div class="col-6">
                                <?= $form->field($model, 'expiry')->widget(MaskedInput::class, [
                                    'mask' => '99/99',
                                    'options' => ['placeholder' => 'ММ/ГГ']
                                ]) ?>
                            </div>
                            <div class="col-6">
                                <?= $form->field($model, 'cvv')->passwordInput([
                                    'placeholder' => '123',
                                    'maxlength' => 3,
                                ]) ?>
                            </div>
                        </div>

                        <?= $form->field($model, 'card_holder')->textInput([
                            'id' => 'card-holder',
                            'placeholder' => 'IVAN IVANOV'
                        ]) ?>

                        <div class="form-group mt-2">
                            <?= Html::submitButton('Оплатить ' . $booking->payment_amount . ' ₽', ['class' => 'btn-payment']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>