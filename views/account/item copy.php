<?php

use app\models\PaymentStatus;
use yii\bootstrap5\Html;
use yii\helpers\Url;

?>
<div class="card mb-3">
    <!-- <img src="/web/img/LK.jpg" class="lk-image" alt="...">
    <div class="card-img-overlay d-flex align-items-center justify-content-center text-align-center">
        <h5 class="text-white"><?= Yii::$app->formatter->asDate($model->arrival_date, 'php:d.m.Y') . " - " . Yii::$app->formatter->asDate($model->departure_date, 'php:d.m.Y') ?></h5>
    </div> -->
    <div style="position: relative;">
        <img src="/web/img/LK.jpg" class="lk-image w-100" alt="...">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white; text-shadow: 1px 1px 2px black; width: 100%;">
            <h1 class="card-title"><?= Yii::$app->formatter->asDate($model->arrival_date, 'php:d.m.Y') . " - " . Yii::$app->formatter->asDate($model->departure_date, 'php:d.m.Y') ?></h1>
        </div>
        <?php if ($model->payment_status == PaymentStatus::getStatusId('pending')): ?>
            <div style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                <h3><span class="badge" style="background-color: #6ab0de; color: white;">Ожидает оплаты</span></h3>
            </div>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <h5 class="card-title fw-bold"><?= $model->statusBooking->title ?></h5>
        <p>Детали бронирования:</p>
        <div>
            <span class="fw-bold text-secondary">Номер:</span> <?= $model->room->roomType->name ?>
        </div>
        <div>
            <span class="fw-bold text-secondary">Цена:</span> <?= $model->price ?> руб
        </div>
        <!-- <div>
            <span class="fw-bold text-secondary">Количество гостей:</span> <?= $model->amount_residents ?>
        </div> -->
        <div>
            <span class="fw-bold text-secondary">Гости:</span>
            <ul class="mb-0">
                <?php foreach ($model->bookingUsers as $bookingUser): ?>
                    <?php $resident = $bookingUser->resident; ?>
                    <li>
                        <?= $resident->surname . ' ' . $resident->name . ($resident->patronymic ? ' ' . $resident->patronymic : '') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>


        <div class="mt-3 d-flex justify-content-between">
            <div>
                <?= Html::a('Подробнее', ['account/view', 'id' => $model->id], ['class' => 'btn register']) ?>
                <?= $model->statusBooking->alias === 'pending'
                    ? Html::a('Отменить поездку', ['change-status', 'id' => $model->id, 'alias' => 'cancelled'], ['class' => 'btn btn-outline-danger', 'data-method' => 'post'])
                    : '' ?>
                <?= $model->statusBooking->alias === 'new'
                    ? Html::a('Отменить поездку', ['change-status', 'id' => $model->id, 'alias' => 'cancelled'], ['class' => 'btn btn-outline-danger', 'data-method' => 'post'])
                    : '' ?>
            </div>
            <div>
                <!-- Кнопка оплаты -->
                <?php if ($model->payment_status == PaymentStatus::getStatusId('pending')): ?>
                    <?php
                    $paymentUrl = '#';
                    if ($model->pay_type_id == 1) {
                        $paymentUrl = ['account/payment', 'id' => $model->id];
                    } elseif ($model->pay_type_id == 3) {
                        $paymentUrl = ['account/payment-card', 'id' => $model->id];
                    }
                    ?>
                    <?= Html::a('Оплатить', $paymentUrl, ['class' => 'btn pay']) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>