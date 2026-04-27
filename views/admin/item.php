<?php

use yii\bootstrap5\Html;
?>
<div class="card mb-3">
    <img src="/web/img/LK.jpg" class="lk-image" alt="...">
    <div class="card-body">
        <h5 class="card-title fw-bold"><?= $model->statusBooking->title ?></h5>
        <p>Детали бронирования:</p>
        <div>
            <span class="fw-bold text-secondary">Номер:</span> <?= $model->room->roomType->name ?>
        </div>
        <div>
            <span class="fw-bold text-secondary">Цена:</span> <?= $model->price ?> руб
        </div>
        <div>
            <span class="fw-bold text-secondary">Дата заселения:</span> <?= Yii::$app->formatter->asDate($model->arrival_date, 'php:d.m.Y') ?>
        </div>
        <div>
            <span class="fw-bold text-secondary">Дата выселения:</span> <?= Yii::$app->formatter->asDate($model->departure_date, 'php:d.m.Y') ?>
        </div>
        <div>
            <span class="fw-bold text-secondary">Количество гостей:</span> <?= $model->amount_residents ?>
        </div>
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


        <div class="mt-3">
            <?= Html::a('Подробнее', ['admin/view', 'id' => $model->id,], ['class' => 'btn btn-primary']) ?>

            <?= $model->statusBooking->alias === 'pending'
                ? Html::a('Подтвердить поездку', ['change-status', 'id' => $model->id, 'alias' => 'new'], ['class' => 'btn btn-outline-primary', 'data-method' => 'post'])
                : '' ?>
        </div>
    </div>
</div>