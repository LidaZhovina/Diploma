<?php

use app\models\Review;
use kartik\rating\StarRating;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Отзывы гостей';
$this->registerCssFile('@web/css/reviews.css');
?>
<div class="reviews-page container">

    <div class="reviews-page-header">
        <h1 class="reviews-page-title">Отзывы <em>гостей</em></h1>
        <p class="reviews-page-sub">Что думают наши гости о санатории «Танхой»</p>
    </div>

    <?php if ($dataProvider->totalCount === 0): ?>
        <div class="reviews-empty">
            <div class="reviews-empty-icon">💬</div>
            <p>Пока нет ни одного отзыва. Станьте первым!</p>
        </div>
    <?php else: ?>

        <div class="reviews-all-grid">
            <?php foreach ($dataProvider->models as $review): ?>
                <?php
                /** @var app\models\Review $review */
                $user    = $review->user;
                $initials = mb_strtoupper(
                    mb_substr($user->surname ?? '', 0, 1) . mb_substr($user->name ?? '', 0, 1)
                );
                ?>
                <div class="review-card">
                    <div class="review-quote">"</div>
                    <div class="review-header">
                        <div class="review-avatar"><?= Html::encode($initials) ?></div>
                        <div>
                            <div class="review-name">
                                <?= Html::encode(($user->surname ?? '') . ' ' . ($user->name ?? '')) ?>
                            </div>
                            <div class="review-date">
                                <?= Yii::$app->formatter->asDate($review->created_at, 'php:d.m.Y') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Звёзды (синие, только чтение) -->
                    <div class="review-stars-wrap">
                        <?= StarRating::widget([
                            'bsVersion' => '5.x',
                            'name'          => 'stars_' . $review->id,
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


        <!-- Пагинация -->
        <div class="reviews-pager">
            <?= LinkPager::widget([
                'pagination' => $dataProvider->pagination,
                'options'    => ['class' => 'pagination justify-content-center'],
            ]) ?>
        </div>

    <?php endif; ?>
</div>