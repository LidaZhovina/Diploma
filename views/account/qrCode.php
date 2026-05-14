<?php

use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var app\models\Booking $booking */
$confirmUrl = \yii\helpers\Url::to(['account/confirm-payment', 'id' => $booking->id], true);
$checkUrl = \yii\helpers\Url::to(['account/check-payment-status', 'id' => $booking->id]);
$bookingId = $booking->id;
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Оплата бронирования №<?= $booking->id ?></h3>
                </div>
                <div class="card-body text-center">
                    <p><strong>Сумма предоплаты (30%):</strong> <?= $booking->payment_amount ?> руб.</p>
                    <p>Остаток оплатите при заезде.</p>
                    <div id="qrcode" class="d-flex justify-content-center mb-3"></div>
                    <p>Отсканируйте QR-код камерой телефона или <strong><?= Html::a('перейдите по ссылке', $confirmUrl) ?></strong> для подтверждения оплаты.</p>
                    <p id="payment-status" class="text-muted">Ожидание оплаты...</p>
                    <?= Html::a('Я оплатил (если не сработало автоматически)', $confirmUrl, ['class' => 'btn btn-success']); ?>
                </div>
            </div>
        </div>
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
                    alert('Оплата подтверждена!');
                    window.location.href = '" . Url::to(['booking/view', 'id' => $bookingId]) . "';
                }
            },
            error: function() {
                console.log('Ошибка при проверке статуса');
            }
        });
    }, 3000); // Опрос каждые 3 секунды
");
?>