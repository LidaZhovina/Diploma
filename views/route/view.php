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
$this->registerCssFile('@web/css/route-view.css');
$this->registerCssFile('@web/css/rewiews.css');

$avgRating = Review::find()->where(['route_id' => $model->id])->average('stars');
$avgRating = $avgRating ? round((float)$avgRating, 1) : 0;
$freeSlots = $model->number_participant - $model->getRouteResidents()->count();
$isFull    = $freeSlots <= 0;

// Резиденты текущего пользователя, которые могут оставить отзыв
$residentsCanReview = [];
if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isClient) {
    $residentsCanReview = Review::getResidentsCanReview(Yii::$app->user->id, $model->id);
}
$canReview = !empty($residentsCanReview);
?>

<div class="rv-page">
    <div class="container">

        <!-- ── НАВИГАЦИЯ ── -->
        <div class="rv-nav">
            <?php if (Yii::$app->user->identity?->isAdmin): ?>
                <?= Html::a('← Все маршруты', ['index'], ['class' => 'btn-back']) ?>
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn-rv-edit']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn-rv-danger',
                    'data'  => ['confirm' => 'Удалить маршрут?', 'method' => 'post'],
                ]) ?>
            <?php elseif (Yii::$app->user->identity?->isClient): ?>
                <?= Html::a('← Личный кабинет', ['account/index'], ['class' => 'btn-back']) ?>
            <?php else: ?>
                <?= Html::a('← Назад', ['/'], ['class' => 'btn-back']) ?>
            <?php endif; ?>
        </div>

        <div class="rv-hero">

            <div class="rv-hero-info">
                <span class="rv-level-badge"><?= Html::encode($model->level->title) ?></span>
                <h1 class="rv-title"><?= Html::encode($model->name) ?></h1>

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

                <div class="rv-pills">
                    <div class="rv-pill">
                        <span class="rv-pill-icon"><i class="ti ti-calendar-event"></i></span>
                        <div>
                            <div class="rv-pill-label">Дата</div>
                            <div class="rv-pill-val">
                                <?= Yii::$app->formatter->asDate($model->date_start, 'php:d.m.Y') ?>
                            </div>
                        </div>
                    </div>
                    <div class="rv-pill">
                        <span class="rv-pill-icon"><i class="ti ti-clock"></i></span>
                        <div>
                            <div class="rv-pill-label">Начало</div>
                            <div class="rv-pill-val">
                                <?= Yii::$app->formatter->asTime($model->time_start, 'php:H:i') ?>
                            </div>
                        </div>
                    </div>
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
                    <div class="rv-pill">
                        <span class="rv-pill-icon"><i class="ti ti-users"></i></span>
                        <div>
                            <div class="rv-pill-label">Свободных мест</div>
                            <div class="rv-pill-val <?= $isFull ? 'rv-pill-val--red' : '' ?>">
                                <?= $isFull
                                    ? 'Мест нет'
                                    : $freeSlots . ' из ' . $model->number_participant ?>
                            </div>
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

        <!-- ── ТЕКСТОВЫЕ БЛОКИ ── -->
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

        <!-- ── ОТЗЫВЫ ── -->
        <div class="rv-reviews">

            <div class="rv-reviews-header">
                <div class="section-title" style="margin-bottom:0">
                    Отзывы <em>участников</em>
                </div>
                <div>
                    <?php if ($canReview): ?>
                        <?= Html::a(
                            '✏ Написать отзыв',
                            ['add-review', 'id' => $model->id],
                            ['class' => 'btn-rv-review']
                        ) ?>
                        <?php if (count($residentsCanReview) > 1): ?>
                            <div style="font-size:12px;color:#999;margin-top:4px;text-align:right">
                                Ещё <?= count($residentsCanReview) ?> гостя без отзыва
                            </div>
                        <?php endif; ?>
                    <?php elseif (
                        !Yii::$app->user->isGuest
                        && Yii::$app->user->identity->isClient
                    ): ?>
                        <?php
                        // Проверяем: участвовал ли хоть один резидент?
                        $wasParticipant = \app\models\RouteResident::find()
                            ->joinWith('resident')
                            ->where([
                                'route_resident.route_id' => $model->id,
                                'resident.user_id'        => Yii::$app->user->id,
                            ])->exists();
                        ?>
                        <?php if ($wasParticipant): ?>
                            <span class="review-already-left">✓ Все отзывы оставлены</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (empty($reviews)): ?>
                <div class="rv-reviews-empty">
                    <p>Отзывов пока нет. После участия в маршруте вы сможете поделиться впечатлениями.</p>
                </div>
            <?php else: ?>
                <div class="reviews-grid">
                    <?php foreach ($reviews as $review): ?>
                        <?php
                        // Показываем резидента если есть, иначе — пользователя
                        $reviewer = $review->resident ?? $review->user;
                        $initials = mb_strtoupper(
                            mb_substr($reviewer->surname ?? '', 0, 1)
                                . mb_substr($reviewer->name ?? '', 0, 1)
                        );
                        $reviewerName = trim(
                            ($reviewer->surname ?? '') . ' ' . ($reviewer->name ?? '')
                        );
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