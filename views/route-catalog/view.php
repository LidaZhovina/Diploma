<?php

use app\models\Review;
use kartik\rating\StarRating;
use yii\helpers\Html;

$this->title = $model->name;
\yii\web\YiiAsset::register($this);
$this->registerCssFile('@web/css/route-view.css');
$this->registerCssFile('@web/css/rewiews.css');

$avgRating = Review::find()->where(['route_id' => $model->id])->average('stars');
$avgRating = $avgRating ? round((float)$avgRating, 1) : 0;
$reviews = Review::find()->with('user')->where(['route_id' => $model->id])->orderBy(['created_at' => SORT_DESC])->all();
?>

<div class="rv-page">
    <div class="container">

        <!-- Навигация -->
        <div class="rv-nav">
            <?= Html::a('← Все маршруты', ['/route-catalog/index'], ['class' => 'btn-back']) ?>
        </div>

        <div class="rv-hero">

            <div class="rv-hero-info">
                <span class="rv-level-badge"><?= Html::encode($model->level->title) ?></span>
                <h1 class="rv-title"><?= Html::encode($model->name) ?></h1>

                <!-- Рейтинг -->
                <div class="rv-rating-row">
                    <?php if ($avgRating > 0): ?>
                        <span class="rv-stars-val"><?= number_format($avgRating, 1) ?></span>
                        <?= StarRating::widget([
                            'bsVersion'     => '5.x',
                            'name'          => 'avg_' . $model->id,
                            'value'         => $avgRating,
                            'pluginOptions' => [
                                'size'        => 'sm',
                                'readonly'    => true,
                                'showClear'   => false,
                                'showCaption' => false,
                                'displayOnly' => true,
                            ],
                        ]) ?>
                        <span class="rv-rating-count"><?= count($reviews) ?> отзыв(ов)</span>
                    <?php else: ?>
                        <span class="rv-no-rating">Нет оценок</span>
                    <?php endif; ?>
                </div>

                <!-- Пилюли — только протяжённость, длительность, цена -->
                <div class="rv-pills">
                    <div class="rv-pill">
                        <span class="rv-pill-icon"><i class="ti ti-hourglass"></i></span>
                        <div>
                            <div class="rv-pill-label">Длительность</div>
                            <div class="rv-pill-val"><?= Html::encode($model->duration) ?></div>
                        </div>
                    </div>
                    <div class="rv-pill">
                        <span class="rv-pill-icon"><i class="ti ti-map-pin"></i></span>
                        <div>
                            <div class="rv-pill-label">Протяжённость</div>
                            <div class="rv-pill-val"><?= $model->length ?> км</div>
                        </div>
                    </div>
                    <div class="rv-pill rv-pill--accent">
                        <span class="rv-pill-icon"><i class="ti ti-coin"></i></span>
                        <div>
                            <div class="rv-pill-label">Стоимость</div>
                            <div class="rv-pill-val rv-pill-val--price">
                                <?= number_format($model->price, 0, '.', ' ') ?> ₽
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Фото -->
            <div class="rv-image-wrap">
                <?php if ($model->routeImage && $model->routeImage->image): ?>
                    <img src="<?= Html::encode($model->imageUrl) ?>"
                        alt="<?= Html::encode($model->name) ?>"
                        class="rv-image">
                <?php else: ?>
                    <div class="rv-image-placeholder">
                        <span>🏔</span>
                        <p>Фото отсутствует</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Описание и экипировка -->
        <?php if ($model->description || $model->outfit): ?>
            <div class="rv-body">
                <?php if ($model->description): ?>
                    <div class="rv-block">
                        <div class="rv-block-title">
                            <span class="rv-block-icon"><i class="ti ti-book"></i></span> Описание маршрута
                        </div>
                        <p class="rv-text"><?= nl2br(Html::encode($model->description)) ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($model->outfit): ?>
                    <div class="rv-block">
                        <div class="rv-block-title">
                            <span class="rv-block-icon"><i class="ti ti-backpack"></i></span> Рекомендуемая экипировка
                        </div>
                        <p class="rv-text"><?= nl2br(Html::encode($model->outfit)) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Отзывы — только просмотр, без возможности написать -->
        <div class="rv-reviews">
            <div class="rv-reviews-header">
                <div class="section-title" style="margin-bottom:0">
                    Отзывы <em>участников</em>
                </div>
            </div>

            <?php if (empty($reviews)): ?>
                <div class="rv-reviews-empty">
                    <p>Отзывов пока нет.</p>
                </div>
            <?php else: ?>
                <div class="reviews-grid">
                    <?php foreach ($reviews as $review): ?>
                        <?php
                        $reviewer = $review->resident ?? $review->user;
                        $initials = mb_strtoupper(
                            mb_substr($reviewer->surname ?? '', 0, 1)
                                . mb_substr($reviewer->name ?? '', 0, 1)
                        );
                        $reviewerName = trim(($reviewer->surname ?? '') . ' ' . ($reviewer->name ?? ''));
                        ?>
                        <div class="review-card">
                            <div class="review-quote">"</div>
                            <div class="review-header">
                                <div class="review-avatar"><?= Html::encode($initials) ?></div>
                                <div>
                                    <div class="review-name"><?= Html::encode($reviewerName) ?></div>
                                    <div class="review-date">
                                        <?= Yii::$app->formatter->asDate($review->created_at, 'php:d.m.Y') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="review-stars-wrap">
                                <?= StarRating::widget([
                                    'bsVersion'     => '5.x',
                                    'name'          => 'rv_' . $review->id,
                                    'value'         => $review->stars,
                                    'pluginOptions' => [
                                        'size'        => 'xs',
                                        'readonly'    => true,
                                        'showClear'   => false,
                                        'showCaption' => false,
                                        'displayOnly' => true,
                                    ],
                                ]) ?>
                            </div>
                            <p class="review-text"><?= Html::encode($review->comment) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>