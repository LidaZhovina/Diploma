<?php

use kartik\rating\StarRating;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Review $model */
/** @var app\models\Route $route */

$this->title = 'Отзыв о маршруте';
$this->registerCssFile('@web/css/form-booking.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
$this->registerCssFile('@web/css/rewiews.css');
?>

<div class="review-form-page container">
    <div class="review-form-wrap">

        <div class="review-form-header">
            <a href="<?= \yii\helpers\Url::to(['route/view', 'id' => $route->id]) ?>" class="btn-back">← Назад</a>
            <h1 class="review-form-title">Отзыв о маршруте</h1>
            <p class="review-form-sub"><?= Html::encode($route->name) ?></p>
        </div>

        <div class="review-form-card">
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
                <div class="f-label">Ваша оценка</div>
                <?= $form->field($model, 'stars')->label(false)->widget(StarRating::class, [
                    'bsVersion' => '5.x',
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

            <!-- Текст -->
            <?= $form->field($model, 'comment')
                ->label('Ваш отзыв')
                ->textarea(['rows' => 5, 'placeholder' => 'Расскажите о маршруте: понравилось ли, что запомнилось...']) ?>

            <div class="review-form-actions">
                <?= Html::submitButton('Отправить отзыв', ['class' => 'btn-booking-next']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>