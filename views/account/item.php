<?php

use app\models\PaymentStatus;
use yii\bootstrap5\Html;
use yii\helpers\Url;

?>
<div class="bk1">
    <div class="bk1-img">
        <!-- Бейдж ожидания оплаты (если есть) -->
        <?php if ($model->payment_status == PaymentStatus::getStatusId('pending')): ?>
            <div class="bk1-badge">Ожидает оплаты</div>
        <?php endif; ?>

        <!-- Картинка (та же, что была) -->
        <img src="/web/img/LK.jpg" class="lk-image" alt="...">

        <!-- Оверлей с датами -->
        <div class="bk1-img-overlay">
            <div class="bk1-date">
                <?= Yii::$app->formatter->asDate($model->arrival_date, 'php:d.m.Y') ?> — <?= Yii::$app->formatter->asDate($model->departure_date, 'php:d.m.Y') ?>
            </div>
        </div>
    </div>
    <div class="bk1-body">
        <div>
            <div class="bk1-status">
                <?php
                $statusText = $model->statusBooking->title;
                echo Html::encode($statusText);
                ?>
            </div>

            <!-- Тип номера -->
            <div class="bk1-room"><?= Html::encode($model->room->roomType->name) ?></div>

            <!-- Строка с ценой, предоплатой и гостями -->
            <div class="bk1-row">
                <div class="bk1-meta">
                    Стоимость
                    <span><?= number_format($model->price, 0, ',', ' ') ?> ₽</span>
                </div>
                <div class="bk1-meta">
                    Предоплата (30%)
                    <span><?= number_format($model->price * 0.3, 0, ',', ' ') ?> ₽</span>
                </div>
                <div class="bk1-meta">
                    Гости
                    <span>
                        <?php
                        $guests = [];
                        foreach ($model->bookingUsers as $bookingUser) {
                            $resident = $bookingUser->resident;
                            $guests[] = trim($resident->surname . ' ' . $resident->name);
                        }
                        echo Html::encode(implode(', ', $guests));
                        ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="bk1-btns">
            <?= Html::a('Подробнее', ['account/view', 'id' => $model->id], ['class' => 'btn register']) ?>

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

            <?php if (in_array($model->statusBooking->alias, ['pending', 'new'])): ?>
                <?= Html::a('Отменить поездку', ['change-status', 'id' => $model->id, 'alias' => 'cancelled'], [
                    'class' => 'btn btn-outline-danger',
                    'data-method' => 'post',
                    'data-confirm' => 'Вы уверены?'
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>