<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Booking $model */

$this->title = 'Детали бронирования';
$this->registerCssFile('@web/css/booking-view.css');
\yii\web\YiiAsset::register($this);

// Подсчёт ночей
$nights = (new \DateTime($model->arrival_date))
    ->diff(new \DateTime($model->departure_date))->days;

// Инициалы для аватаров гостей
function getInitials($surname, $name)
{
    return mb_strtoupper(mb_substr($surname, 0, 1) . mb_substr($name, 0, 1));
}

// Определяем цвет статуса бронирования
$statusColors = [
    'pending'   => ['bg' => 'rgba(255,255,255,0.18)', 'dot' => '#FAC775', 'label' => 'В обработке'],
    'new'       => ['bg' => 'rgba(255,255,255,0.18)', 'dot' => '#B4E5F8', 'label' => 'Подтверждено'],
    'active'    => ['bg' => 'rgba(255,255,255,0.18)', 'dot' => '#5DCAA5', 'label' => 'Активно'],
    'past'      => ['bg' => 'rgba(255,255,255,0.18)', 'dot' => '#AFA9EC', 'label' => '✓ Завершена'],
    'cancelled' => ['bg' => 'rgba(255,0,0,0.15)',     'dot' => '#F09595', 'label' => '✕ Отменено'],
];
$alias = $model->statusBooking->alias ?? 'pending';
$statusCfg = $statusColors[$alias] ?? $statusColors['pending'];
$statusLabel = $statusCfg['label'];

// Статус оплаты
$isPaid = $model->paymentStatus->title === 'paid';
?>

<div class="bv-page">
    <div class="bv-wrap">

        <?= Html::a(
            '<i class="ti ti-arrow-left"></i> Мои бронирования',
            ['index'],
            ['class' => 'bv-back']
        ) ?>

        <div class="bv-card">

            <!-- Шапка с градиентом -->
            <div class="bv-hero">
                <div class="bv-hero-status" style="background: <?= $statusCfg['bg'] ?>">
                    <span class="bv-dot" style="background: <?= $statusCfg['dot'] ?>"></span>
                    <?= $statusLabel ?>
                </div>
                <div class="bv-hero-title">
                    <?= Html::encode($model->room->roomType->name) ?>
                </div>
                <div class="bv-hero-sub">
                    <?= $model->room->number_guests ?>-местный · Санаторий «Танхой»
                </div>
                <div class="bv-hero-dates">
                    <div class="bv-date-pill">
                        <div class="bv-date-label">Заезд</div>
                        <div class="bv-date-val">
                            <?= Yii::$app->formatter->asDate($model->arrival_date, 'php:d.m.Y') ?>
                        </div>
                    </div>
                    <div class="bv-arrow">→</div>
                    <div class="bv-date-pill">
                        <div class="bv-date-label">Выезд</div>
                        <div class="bv-date-val">
                            <?= Yii::$app->formatter->asDate($model->departure_date, 'php:d.m.Y') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Детали бронирования -->
            <div class="bv-section">
                <div class="bv-section-title">
                    <div class="bv-icon"><i class="ti ti-clipboard-list"></i></div>
                    Детали бронирования
                </div>
                <div class="bv-rows">
                    <div class="bv-row">
                        <span class="bv-row-label">Номер бронирования</span>
                        <span class="bv-row-val">#<?= $model->id ?></span>
                    </div>
                    <div class="bv-row">
                        <span class="bv-row-label">Тип номера</span>
                        <span class="bv-row-val">
                            <?= Html::encode($model->room->roomType->name) ?>, <?= $model->room->number_guests ?>-местный
                        </span>
                    </div>
                    <div class="bv-row">
                        <span class="bv-row-label">Количество ночей</span>
                        <span class="bv-row-val"><?= $nights ?> ночей</span>
                    </div>
                    <div class="bv-row">
                        <span class="bv-row-label">Количество гостей</span>
                        <span class="bv-row-val"><?= $model->amount_residents ?> человека</span>
                    </div>
                    <div class="bv-row">
                        <span class="bv-row-label">Контактный телефон</span>
                        <span class="bv-row-val"><?= Html::encode($model->contact_phone) ?></span>
                    </div>
                    <div class="bv-row">
                        <span class="bv-row-label">Способ оплаты</span>
                        <span class="bv-row-val"><?= Html::encode($model->payType->title) ?></span>
                    </div>
                    <div class="bv-row">
                        <span class="bv-row-label">Статус оплаты</span>
                        <span class="bv-row-val <?= $isPaid ? 'bv-paid' : 'bv-unpaid' ?>">
                            <?= $isPaid ? '✓ Оплачено' : '⏳ Ожидает оплаты' ?>
                        </span>
                    </div>
                    <?php if ($model->comment): ?>
                        <div class="bv-row">
                            <span class="bv-row-label">Комментарий</span>
                            <span class="bv-row-val"><?= Html::encode($model->comment) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($model->route_id) && $model->route): ?>
                        <div class="bv-row">
                            <span class="bv-row-label">Маршрут</span>
                            <span class="bv-row-val"><?= Html::encode($model->route->name) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->reason): ?>
                        <div class="bv-row">
                            <span class="bv-row-label" style="color:#E24B4A">Причина отмены</span>
                            <span class="bv-row-val" style="color:#E24B4A">
                                <?= Html::encode($model->reason->comment) ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Гости и программы -->
            <div class="bv-section">
                <div class="bv-section-title">
                    <div class="bv-icon"><i class="ti ti-users"></i></div>
                    Гости и программы
                </div>
                <div class="bv-guests">
                    <?php foreach ($model->bookingUsers as $i => $bookingUser): ?>
                        <?php $resident = $bookingUser->resident; ?>
                        <div class="bv-guest">
                            <div class="bv-guest-av">
                                <?= getInitials($resident->surname, $resident->name) ?>
                            </div>
                            <div>
                                <div class="bv-guest-name">
                                    <?= Html::encode($resident->surname . ' ' . $resident->name . ($resident->patronymic ? ' ' . $resident->patronymic : '')) ?>
                                </div>
                                <div class="bv-guest-prog">
                                    <?= $resident->wellnessProgram
                                        ? 'Программа: ' . Html::encode($resident->wellnessProgram->title)
                                        : 'Без программы' ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Стоимость -->
            <div class="bv-section">
                <div class="bv-section-title">
                    <div class="bv-icon"><i class="ti ti-credit-card"></i></div>
                    Стоимость
                </div>
                <div class="bv-price-block">
                    <div>
                        <div class="bv-price-lbl">Предоплата (30%)</div>
                        <div class="bv-price-note">
                            <?= $isPaid ? 'Остаток оплачен при заезде' : 'Остаток оплатите при заезде' ?>
                        </div>
                    </div>
                    <div style="text-align:right">
                        <div class="bv-price-num">
                            <?= number_format($model->payment_amount, 0, '.', ' ') ?> ₽
                        </div>
                        <span class="bv-price-total">
                            <?= number_format($model->price, 0, '.', ' ') ?> ₽ всего
                        </span>
                    </div>
                </div>
            </div>

            <!-- Кнопки -->
            <div class="bv-actions">

                <?php if ($model->statusBooking->alias === 'past'): ?>
                    <?php if (!\app\models\Review::hasBookingReview(Yii::$app->user->id, $model->id)): ?>
                        <?= Html::a(
                            '<i class="ti ti-pencil"></i> Оставить отзыв',
                            ['account/add-review', 'id' => $model->id],
                            ['class' => 'btn-bv-review', 'encode' => false]
                        ) ?>
                    <?php else: ?>
                        <span class="btn-bv-review-done">
                            <i class="ti ti-check"></i> Отзыв оставлен
                        </span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!$isPaid && in_array($alias, ['pending', 'new'])): ?>
                    <?php
                    $payUrl = '#';
                    if ($model->pay_type_id == 1) $payUrl = ['account/payment', 'id' => $model->id];
                    elseif ($model->pay_type_id == 3) $payUrl = ['account/payment-card', 'id' => $model->id];
                    ?>
                    <?= Html::a('💳 Оплатить', $payUrl, ['class' => 'btn-bv-pay']) ?>
                <?php endif; ?>

                <?php if (in_array($alias, ['pending', 'new'])): ?>
                    <?= Html::a(
                        'Отменить поездку',
                        ['change-status', 'id' => $model->id, 'alias' => 'cancelled'],
                        [
                            'class' => 'btn-bv-cancel',
                            'data-method' => 'post',
                            'data-confirm' => 'Вы уверены, что хотите отменить бронирование?'
                        ]
                    ) ?>
                <?php endif; ?>

                <?= Html::a(
                    '<i class="ti ti-arrow-left"></i> Назад',
                    ['index'],
                    ['class' => 'btn-bv-back', 'encode' => false]
                ) ?>

            </div>

        </div>

    </div>
</div>