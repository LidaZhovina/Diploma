<?php

/** @var yii\web\View $this */
/** @var app\models\Booking $model */

use yii\helpers\Html;
use yii\helpers\Url;

$statusAlias  = $model->statusBooking->alias ?? '';
$mainResident = $model->mainResident; // relation: first resident where is_main_guest=1

// Метка статуса
$badgeMap = [
    'new'       => ['class' => 'bk-badge--upcoming',  'label' => 'Предстоящая'],
    'pending'   => ['class' => 'bk-badge--checkout',  'label' => 'В обработке'],
    'active'    => ['class' => 'bk-badge--active',    'label' => 'Активная'],
    'past'      => ['class' => 'bk-badge--cancelled', 'label' => 'Прошедшая'],
    'cancelled' => ['class' => 'bk-badge--cancelled', 'label' => 'Отменена'],
];
$badge = $badgeMap[$statusAlias] ?? ['class' => 'bk-badge--cancelled', 'label' => $statusAlias];

// Подсветка превью
$thumbExtra = ($statusAlias === 'active') ? ' bk-thumb--active' : '';

// Кол-во ночей
$arrival   = new DateTime($model->arrival_date);
$departure = new DateTime($model->departure_date);
$nights    = $arrival->diff($departure)->days;

// Плашка "Выезд сегодня"
$isCheckoutToday = ($statusAlias === 'active'
    && $model->departure_date === date('Y-m-d'));
?>

<div class="bk-card">

    <!-- Превью с датами -->
    <div class="bk-thumb<?= $thumbExtra ?>">
        <?php if ($isCheckoutToday): ?>
            <span class="bk-thumb-badge">Выезд сегодня</span>
        <?php endif; ?>
        <div class="bk-thumb-dates">
            <?= Yii::$app->formatter->asDate($model->arrival_date, 'php:d.m') ?><br>
            <?= Yii::$app->formatter->asDate($model->departure_date, 'php:d.m') ?>
        </div>
        <div class="bk-thumb-nights"><?= $nights ?> ночей</div>
    </div>

    <!-- Тело -->
    <div class="bk-body">
        <div class="bk-top">
            <span class="bk-badge <?= $badge['class'] ?>">
                <?= Html::encode($badge['label']) ?>
            </span>
            <div class="bk-room-name">
                <?= Html::encode($model->room->roomType->name ?? '—') ?>
            </div>
            <div class="bk-meta">
                <?php if ($mainResident): ?>
                    <?= Html::encode(
                        $mainResident->surname . ' '
                            . mb_substr($mainResident->name, 0, 1) . '.'
                            . mb_substr($mainResident->patronymic, 0, 1) . '.'
                    ) ?> ·
                <?php endif; ?>
                <?= $model->amount_residents ?> <?= Yii::t('app', '{n, plural, one{гость} few{гостя} many{гостей} other{гостей}}', ['n' => $model->amount_residents]) ?>
                · ком. <?= Html::encode($model->room->number) ?>
            </div>
        </div>

        <!-- Кнопки по статусу -->
        <div class="bk-actions">
            <?= Html::a(
                '<i class="ti ti-eye"></i> Подробнее',
                ['reseption/view', 'id' => $model->id],
                ['class' => 'btn-bk btn-bk--ghost']
            ) ?>

            <?php if ($statusAlias === 'new' || $statusAlias === 'pending'): ?>
                <?= Html::a(
                    '<i class="ti ti-home-check"></i> Заселить',
                    ['reseption/check-in', 'id' => $model->id],
                    ['class' => 'btn-bk btn-bk--checkin']
                ) ?>
                <?= Html::a(
                    '<i class="ti ti-ban"></i> Отменить',
                    ['reseption/reason', 'id' => $model->id],
                    [
                        'class' => 'btn-bk btn-bk--cancel',
                        'data-confirm' => 'Отменить бронирование?',
                        'data-method'  => 'post',
                    ]
                ) ?>
            <?php endif; ?>

            <?php if ($statusAlias === 'active'): ?>
                <?= Html::a(
                    '<i class="ti ti-home-move"></i> Выселить',
                    ['reseption/check-out', 'id' => $model->id],
                    ['class' => 'btn-bk btn-bk--checkout']
                ) ?>
            <?php endif; ?>
        </div>
    </div>

</div>