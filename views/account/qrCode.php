<?php

use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var app\models\Booking $booking */

$confirmUrl = \yii\helpers\Url::to(['account/confirm-payment', 'id' => $booking->id], true);
$checkUrl   = \yii\helpers\Url::to(['account/check-payment-status', 'id' => $booking->id]);
$bookingId  = $booking->id;

$this->registerCssFile('@web/css/form-booking.css');
$this->registerCssFile('@web/css/cardPayment.css');
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
            <div class="bk-title">Оплата по QR-коду</div>
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

        <!-- QR -->
        <div class="bk-section" style="text-align:center">
            <div class="bk-section-title" style="justify-content:center">
                <div class="bk-icon"><i class="ti ti-qrcode"></i></div> Сканируйте камерой телефона
            </div>

            <div id="qrcode" class="d-flex justify-content-center mb-3"></div>

            <div id="payment-status" class="qr-status qr-status--waiting">
                <span class="qr-status-dot"></span>
                Ожидание оплаты...
            </div>

            <p style="font-size:13px;color:#888;margin:12px 0 20px;line-height:1.6">
                Или <?= Html::a('перейдите по ссылке', $confirmUrl, ['style' => 'color:#669CFF;font-weight:600']) ?>
                для подтверждения оплаты
            </p>

            <?= Html::a(
                'Я оплатил (если не сработало автоматически)',
                $confirmUrl,
                ['class' => 'btn-booking-next', 'id' => 'btn-confirm']
            ) ?>
        </div>

        <?= Html::a('← Назад', ['account/index'], ['class' => 'btn-booking-back', 'style' => 'margin-top:8px']) ?>

    </div>
</div>

<?php
$this->registerJsFile('@web/js/qrcode.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJs("
    new QRCode(document.getElementById('qrcode'), {
        text: '{$confirmUrl}',
        width: 200,
        height: 200
    });
    let checkInterval = setInterval(function() {
        $.ajax({
            url: '" . Url::to(['account/check-payment-status']) . "',
            type: 'GET',
            data: { id: {$bookingId} },
            success: function(response) {
                if (response.paid === true) {
                    // Останавливаем таймер
                    clearInterval(checkInterval);
                    
                    // Показываем сообщение и редиректим
                    // alert('Оплата подтверждена!');
                    window.location.href = '" . Url::to(['account/view', 'id' => $bookingId]) . "';
                }
            },
            error: function() {
                console.log('Ошибка при проверке статуса');
            }
        });
    }, 3000); // Опрос каждые 3 секунды
");
?>