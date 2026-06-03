<?php

use kartik\rating\StarRating;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Review $model */
/** @var app\models\Booking $booking */

$this->title = 'Оставить отзыв';
$this->registerCssFile('@web/css/form-booking.css');
$this->registerCssFile('@web/css/reviews.css');
?>

<div class="booking-page">
    <div class="booking-wrap">

        <div class="bk-header">
            <a href="<?= \yii\helpers\Url::to(['account/view', 'id' => $booking->id]) ?>"
               class="btn-back" style="display:inline-flex;align-items:center;gap:6px;color:#999;font-size:13px;text-decoration:none;margin-bottom:12px;">
                ← Назад к бронированию
            </a>
            <div class="bk-title">Оставить отзыв</div>
            <div class="bk-sub">
                <?= Html::encode($booking->room->roomType->name) ?> ·
                <?= Yii::$app->formatter->asDate($booking->arrival_date, 'php:d.m.Y') ?>
                — <?= Yii::$app->formatter->asDate($booking->departure_date, 'php:d.m.Y') ?>
            </div>
        </div>

        <div class="bk-section">

            <?php $form = ActiveForm::begin([
                'id'          => 'review-form',
                'fieldConfig' => [
                    'template'     => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'f-label'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'booking_id')->hiddenInput()->label(false) ?>

            <!-- Звёзды -->
            <div class="review-stars-block">
                <div class="bk-section-title">
                    <div class="bk-icon">⭐</div> Ваша оценка
                </div>
                <?= $form->field($model, 'stars')->label(false)->widget(StarRating::class, [
                    'bsVersion'     => '5.x',
                    'pluginOptions' => [
                        'size'        => 'xl',
                        'showClear'   => false,
                        'showCaption' => false,
                        'min'         => 1,
                        'max'         => 5,
                        'step'        => 1,
                    ],
                ]) ?>
            </div>

            <!-- Текст отзыва -->
            <div style="margin-top:20px">
                <div class="bk-section-title">
                    <div class="bk-icon">💬</div> Ваш отзыв
                </div>
                <?= $form->field($model, 'comment')->label(false)
                    ->textarea(['rows' => 5, 'placeholder' => 'Расскажите о своём впечатлении от пребывания...']) ?>
            </div>

            <div style="margin-top:20px">
                <?= Html::submitButton('Отправить отзыв', ['class' => 'btn-booking-next']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>