<?php

use app\models\Review;
use kartik\rating\StarRating;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Route $model */
/** @var array $reviews */
/** @var bool $canReview */

$this->title = $model->name;
\yii\web\YiiAsset::register($this);
$this->registerCssFile('@web/css/route-view.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);

// Средний рейтинг из таблицы review
$avgRating = Review::find()
    ->where(['route_id' => $model->id])
    ->average('stars');
$avgRating = $avgRating ? round((float)$avgRating, 1) : 0;

$freeSlots = $model->number_participant - $model->getRouteResidents()->count();
?>

<div class="route-view-page container">

    <!-- ══ ШАПКА ══ -->
    <div class="rv-header">

        <!-- Кнопки назад/управление -->
        <div class="rv-nav">
            <?php if (Yii::$app->user->identity?->isAdmin): ?>
                <?= Html::a('← Все маршруты', ['index'], ['class' => 'btn-back']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn-rv-danger',
                    'data'  => ['confirm' => 'Удалить маршрут?', 'method' => 'post'],
                ]) ?>
            <?php elseif (Yii::$app->user->identity?->isClient): ?>
                <?= Html::a('← Личный кабинет', ['account/index'], ['class' => 'btn-back']) ?>
            <?php endif; ?>
        </div>

        <!-- Заголовок + тег уровня -->
        <div class="rv-title-row">
            <span class="rv-level-badge"><?= Html::encode($model->level->title) ?></span>
            <h1 class="rv-title"><?= Html::encode($model->name) ?></h1>
        </div>

        <!-- Средний рейтинг -->
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
            <?php else: ?>
                <span class="rv-no-rating">Нет оценок</span>
            <?php endif; ?>
        </div>

    </div>

    <!-- ══ ОСНОВНОЙ КОНТЕНТ: фото + детали ══ -->
    <div class="rv-layout">

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

        <!-- Детали -->
        <div class="rv-info">

            <!-- Быстрые пилюли -->
            <div class="rv-pills">
                <div class="rv-pill">
                    <span class="rv-pill-icon">📅</span>
                    <div>
                        <div class="rv-pill-label">Дата</div>
                        <div class="rv-pill-val">
                            <?= Yii::$app->formatter->asDate($model->date_start, 'php:d.m.Y') ?>
                        </div>
                    </div>
                </div>
                <div class="rv-pill">
                    <span class="rv-pill-icon">🕐</span>
                    <div>
                        <div class="rv-pill-label">Начало</div>
                        <div class="rv-pill-val">
                            <?= Yii::$app->formatter->asTime($model->time_start, 'php:H:i') ?>
                        </div>
                    </div>
                </div>
                <div class="rv-pill">
                    <span class="rv-pill-icon">⏱</span>
                    <div>
                        <div class="rv-pill-label">Длительность</div>
                        <div class="rv-pill-val"><?= Html::encode($model->duration) ?></div>
                    </div>
                </div>
                <div class="rv-pill">
                    <span class="rv-pill-icon">📏</span>
                    <div>
                        <div class="rv-pill-label">Протяжённость</div>
                        <div class="rv-pill-val"><?= $model->length ?> км</div>
                    </div>
                </div>
                <div class="rv-pill">
                    <span class="rv-pill-icon">👥</span>
                    <div>
                        <div class="rv-pill-label">Свободных мест</div>
                        <div class="rv-pill-val <?= $freeSlots === 0 ? 'rv-pill-val--red' : '' ?>">
                            <?= $freeSlots > 0 ? $freeSlots . ' из ' . $model->number_participant : 'Мест нет' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Цена -->
            <div class="rv-price-block">
                <div class="rv-price-label">Стоимость участия</div>
                <div class="rv-price"><?= number_format($model->price, 0, '.', ' ') ?> ₽</div>
            </div>

            <!-- Описание -->
            <?php if ($model->description): ?>
                <div class="rv-section">

                    <div class="section-title" style="margin-bottom:0, mt-5">
                        Описание
                    </div>


                    <p class="rv-text"><?= nl2br(Html::encode($model->description)) ?></p>
                </div>
            <?php endif; ?>

            <!-- Экипировка -->
            <?php if ($model->outfit): ?>
                <div class="rv-section">
                    <div class="rv-section-title">
                        <span class="rv-section-icon">🎒</span> Рекомендуемая экипировка
                    </div>
                    <p class="rv-text"><?= nl2br(Html::encode($model->outfit)) ?></p>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- ══ ОТЗЫВЫ УЧАСТНИКОВ ══ -->
    <div class="route-reviews-section">

        <div class="route-reviews-header">
            <div>
                <div class="section-title" style="margin-bottom:0">
                    Отзывы <em>участников</em>
                </div>
            </div>

            <?php if ($canReview ?? false): ?>
                <?= Html::a('✏ Написать отзыв', ['add-review', 'id' => $model->id], ['class' => 'btn-wellness']) ?>
            <?php elseif (
                !Yii::$app->user->isGuest
                && Yii::$app->user->identity->isClient
                && Review::hasRouteReview(Yii::$app->user->id, $model->id)
            ): ?>
                <span class="review-already-left">✓ Вы уже оставили отзыв</span>
            <?php endif; ?>
        </div>

        <?php if (empty($reviews)): ?>
            <div class="route-reviews-empty">
                <p>Отзывов пока нет. После участия в маршруте вы сможете поделиться впечатлениями.</p>
            </div>
        <?php else: ?>
            <div class="reviews-grid">
                <?php foreach ($reviews as $review): ?>
                    <?php
                    $user     = $review->user;
                    $initials = mb_strtoupper(
                        mb_substr($user->surname ?? '', 0, 1)
                            . mb_substr($user->name ?? '', 0, 1)
                    );
                    ?>
                    <div class="review-card">
                        <div class="review-quote">"</div>
                        <div class="review-header">
                            <div class="review-avatar"><?= Html::encode($initials) ?></div>
                            <div>
                                <div class="review-name">
                                    <?= Html::encode(trim(($user->surname ?? '') . ' ' . ($user->name ?? ''))) ?>
                                </div>
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