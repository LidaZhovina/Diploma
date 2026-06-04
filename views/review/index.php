<?php

use app\models\Review;
use kartik\rating\StarRating;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $bookingReviews */
/** @var yii\data\ActiveDataProvider $routeReviews */

$this->title = 'Отзывы гостей';
$this->registerCssFile('@web/css/reviews.css');
$this->registerCssFile('@web/css/admin-reviews.css');
?>

<div class="body ar-page container">

    <div class="ar-header">
        <h1 class="ar-title">Отзывы <em>гостей</em></h1>
        <p class="ar-sub">Все отзывы на бронирования и маршруты</p>
    </div>

    <div class="ar-grid">

        <!-- ── Колонка: Отзывы на бронирования ── -->
        <div class="ar-col">
            <div class="ar-col-header">
                <div class="ar-col-icon"><i class="ti ti-home"></i></div>
                <div>
                    <div class="ar-col-title">На бронирования</div>
                    <div class="ar-col-count"><?= $bookingReviews->totalCount ?> отзывов</div>
                </div>
            </div>

            <?php if ($bookingReviews->totalCount === 0): ?>
                <div class="ar-empty">
                    <div class="ar-empty-icon">💬</div>
                    <p>Отзывов пока нет</p>
                </div>
            <?php else: ?>
                <div class="ar-list">
                    <?php foreach ($bookingReviews->models as $review): ?>
                        <?php
                        /** @var Review $review */
                        $user = $review->user;
                        $initials = mb_strtoupper(
                            mb_substr($user->surname ?? '', 0, 1)
                                . mb_substr($user->name ?? '', 0, 1)
                        );
                        ?>
                        <div class="ar-card">
                            <div class="ar-card-top">
                                <div class="ar-avatar"><?= Html::encode($initials) ?></div>
                                <div class="ar-card-meta">
                                    <div class="ar-card-name">
                                        <?= Html::encode(($user->surname ?? '') . ' ' . ($user->name ?? '')) ?>
                                    </div>
                                    <div class="ar-card-date">
                                        <?= Yii::$app->formatter->asDate($review->created_at, 'php:d.m.Y') ?>
                                    </div>
                                </div>
                                <div class="ar-card-actions">
                                    <?= Html::a(
                                        '<i class="ti ti-trash"></i>',
                                        ['delete', 'id' => $review->id],
                                        [
                                            'encode'       => false,
                                            'class'        => 'ar-delete-btn',
                                            'data-method'  => 'post',
                                            'data-confirm' => 'Удалить отзыв?',
                                            'title'        => 'Удалить',
                                        ]
                                    ) ?>
                                </div>
                            </div>

                            <div class="ar-stars">
                                <?= StarRating::widget([
                                    'bsVersion'     => '5.x',
                                    'name'          => 'br_' . $review->id,
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

                            <p class="ar-text"><?= Html::encode($review->comment) ?></p>

                            <?php if ($review->booking): ?>
                                <div class="ar-ref">
                                    <i class="ti ti-calendar"></i>
                                    Бронирование #<?= $review->booking_id ?> ·
                                    <?= Html::encode($review->booking->room->roomType->name ?? '') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="ar-pager">
                    <?= LinkPager::widget([
                        'pagination' => $bookingReviews->pagination,
                        'options'    => ['class' => 'pagination justify-content-center'],
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ── Колонка: Отзывы на маршруты ── -->
        <div class="ar-col">
            <div class="ar-col-header">
                <div class="ar-col-icon"><i class="ti ti-mountain"></i></div>
                <div>
                    <div class="ar-col-title">На маршруты</div>
                    <div class="ar-col-count"><?= $routeReviews->totalCount ?> отзывов</div>
                </div>
            </div>

            <?php if ($routeReviews->totalCount === 0): ?>
                <div class="ar-empty">
                    <div class="ar-empty-icon">🏔</div>
                    <p>Отзывов пока нет</p>
                </div>
            <?php else: ?>
                <div class="ar-list">
                    <?php foreach ($routeReviews->models as $review): ?>
                        <?php
                        /** @var Review $review */
                        $user = $review->user;
                        $initials = mb_strtoupper(
                            mb_substr($user->surname ?? '', 0, 1)
                                . mb_substr($user->name ?? '', 0, 1)
                        );
                        ?>
                        <div class="ar-card">
                            <div class="ar-card-top">
                                <div class="ar-avatar"><?= Html::encode($initials) ?></div>
                                <div class="ar-card-meta">
                                    <div class="ar-card-name">
                                        <?= Html::encode(($user->surname ?? '') . ' ' . ($user->name ?? '')) ?>
                                    </div>
                                    <div class="ar-card-date">
                                        <?= Yii::$app->formatter->asDate($review->created_at, 'php:d.m.Y') ?>
                                    </div>
                                </div>
                                <div class="ar-card-actions">
                                    <?= Html::a(
                                        '<i class="ti ti-trash"></i>',
                                        ['delete', 'id' => $review->id],
                                        [
                                            'encode'       => false,
                                            'class'        => 'ar-delete-btn',
                                            'data-method'  => 'post',
                                            'data-confirm' => 'Удалить отзыв?',
                                            'title'        => 'Удалить',
                                        ]
                                    ) ?>
                                </div>
                            </div>

                            <div class="ar-stars">
                                <?= StarRating::widget([
                                    'bsVersion'     => '5.x',
                                    'name'          => 'rr_' . $review->id,
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

                            <p class="ar-text"><?= Html::encode($review->comment) ?></p>

                            <?php if ($review->route): ?>
                                <div class="ar-ref">
                                    <i class="ti ti-route"></i>
                                    <?= Html::encode($review->route->name) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="ar-pager">
                    <?= LinkPager::widget([
                        'pagination' => $routeReviews->pagination,
                        'options'    => ['class' => 'pagination justify-content-center'],
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>