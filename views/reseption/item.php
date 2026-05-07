<?php

use yii\bootstrap5\Html;
?>
<div class="card mb-3">
    <div style="position: relative;">
        <img src="/web/img/LK.jpg" class="lk-image w-100" alt="...">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white; text-shadow: 1px 1px 2px black; width: 100%;">
            <h1 class="card-title"><?= Yii::$app->formatter->asDate($model->arrival_date, 'php:d.m.Y') . " - " . Yii::$app->formatter->asDate($model->departure_date, 'php:d.m.Y') ?></h1>
        </div>
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


        <div class="mt-3">
            <?= Html::a('Подробнее', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <!-- Кнопки смены статуса для ресепшн -->
            <?= $model->statusBooking->alias === 'new'
                ? Html::a('Заселить', ['check-in', 'id' => $model->id], ['class' => 'btn btn-outline-primary'])
                : '' ?>
            <?= $model->statusBooking->alias === 'active'
                ? Html::a('Закрыть поездку', ['check-out', 'id' => $model->id, 'alias' => 'past'], ['class' => 'btn btn-outline-primary', 'data-method' => 'post'])
                : '' ?>
        </div>
    </div>
</div>