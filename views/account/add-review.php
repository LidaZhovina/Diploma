<?php

use kartik\rating\StarRating;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Review $model */
/** @var app\models\Route $route */

$this->title = 'Отзыв о маршруте';
// rewiews содержит review-stars-block и прочие стили отзыва
$this->registerCssFile('@web/css/form-booking.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
$this->registerCssFile('@web/css/rewiews.css',      ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
?>

<div class="booking-page">
    <div class="booking-wrap">

        <!-- Назад -->
        <?= Html::a(
            '← Назад к маршруту',
            ['route/view', 'id' => $route->id],
            ['class' => 'bv-back', 'style' => 'display:inline-flex;align-items:center;gap:6px;color:#888;font-size:13px;font-weight:600;text-decoration:none;margin-bottom:20px;font-family:Montserrat,sans-serif;']
        ) ?>

        <!-- Шапка -->
        <div class="bk-header">
            <div class="bk-title">Отзыв о маршруте</div>
            <div class="bk-sub"><?= Html::encode($route->name) ?></div>
        </div>

        <!-- Карточка маршрута-подсказка -->
        <div class="bk-section" style="margin-bottom:12px">
            <div class="bk-section-title">
                <div class="bk-icon">🏔</div> Маршрут
            </div>
            <div style="display:flex;gap:24px;flex-wrap:wrap;font-size:13px;color:#555">
                <div>
                    <span style="color:#888;font-size:11px;text-transform:uppercase;letter-spacing:.5px;font-weight:700;display:block;margin-bottom:2px">Дата</span>
                    <?= Yii::$app->formatter->asDate($route->date_start, 'php:d.m.Y') ?>
                </div>
                <div>
                    <span style="color:#888;font-size:11px;text-transform:uppercase;letter-spacing:.5px;font-weight:700;display:block;margin-bottom:2px">Сложность</span>
                    <?= Html::encode($route->level->title ?? '') ?>
                </div>
                <div>
                    <span style="color:#888;font-size:11px;text-transform:uppercase;letter-spacing:.5px;font-weight:700;display:block;margin-bottom:2px">Длительность</span>
                    <?= Html::encode($route->duration) ?>
                </div>
            </div>
        </div>

        <!-- Форма -->
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
            <?= $form->field($model, 'route_id')->hiddenInput()->label(false) ?>

            <!-- Звёзды -->
            <div class="review-stars-block">
                <div class="bk-section-title" style="margin-bottom:10px">
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
                <div class="bk-section-title" style="margin-bottom:10px">
                    <div class="bk-icon">💬</div> Ваш отзыв
                </div>
                <?= $form->field($model, 'comment')->label(false)
                    ->textarea(['rows' => 5, 'placeholder' => 'Расскажите о маршруте: понравилось ли, что запомнилось, что взять с собой...']) ?>
            </div>

            <div style="margin-top:20px">
                <?= Html::submitButton('Отправить отзыв', ['class' => 'btn-booking-next']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>