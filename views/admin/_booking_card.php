<?php
use app\models\PaymentStatus;
use yii\bootstrap5\Html;

/** @var app\models\Booking $model */
$nights = (new \DateTime($model->arrival_date))->diff(new \DateTime($model->departure_date))->days;
?>
<div class="bk-card">
    <div class="bk-thumb" style="background: linear-gradient(160deg, #4a56a6, #3B4593);">
        <?php if ($model->payment_status == PaymentStatus::getStatusId('pending')): ?>
            <div class="bk-ovl" style="background:rgba(102,156,255,0.25);color:#B4E5F8;border:0.5px solid rgba(102,156,255,0.5);">Ожидает оплаты</div>
        <?php endif; ?>
        <div class="bk-dates"><?= Yii::$app->formatter->asDate($model->arrival_date, 'php:d') ?>–<?= Yii::$app->formatter->asDate($model->departure_date, 'php:d.m') ?></div>
        <div class="bk-nights"><?= $nights ?> ночей</div>
    </div>
    <div class="bk-body">
        <div class="bk-top">
            <span class="badge <?= match($model->statusBooking->alias) {
                'pending' => 'b-pending',
                'new' => 'b-new',
                'active' => 'b-active',
                'past' => 'b-past',
                'cancelled' => 'b-cancel',
                default => ''
            } ?>"><?= $model->statusBooking->title ?></span>
            <div class="bk-room"><?= Html::encode($model->room->roomType->name) ?> <?= $model->room->number_guests ?>-местный</div>
            <div class="bk-meta">
                <?php
                $mainResident = $model->getMainResident();
                $guestName = $mainResident ? $mainResident->surname . ' ' . mb_substr($mainResident->name, 0, 1) . '.' : '—';
                ?>
                <?= Html::encode($guestName) ?> · <?= $model->amount_residents ?> гостя
            </div>
        </div>
        <div class="bk-btns">
            <?= Html::a('Подробнее', ['admin/view', 'id' => $model->id], ['class' => 'bbtn bb-ghost']) ?>
            <?php if ($model->statusBooking->alias === 'pending'): ?>
                <?= Html::a('Подтвердить', ['change-status', 'id' => $model->id, 'alias' => 'new'], ['class' => 'bbtn bb-blue', 'data-method' => 'post']) ?>
            <?php endif; ?>
        </div>
    </div>
</div>