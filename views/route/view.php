<?php

use kartik\rating\StarRating;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Route $model */
/** @var yii\widgets\ActiveForm $form */


$this->title = $model->name;

\yii\web\YiiAsset::register($this);
?>
<div class="route-view">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <p class="text-end">
        <?= Yii::$app->user->identity?->isAdmin
            ? Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            : '' ?>
        <?= Yii::$app->user->identity?->isAdmin
            ? Html::a('Назад', ['index', 'id' => $model->id], ['class' => 'btn btn-primary'])
            : '' ?>
        <?= Yii::$app->user->identity?->isClient
            ? Html::a('Назад', ['account/index', 'id' => $model->id], ['class' => 'btn btn-primary'])
            : '' ?>
    </p>

    <?php
    $avgRating = $model->stars ?? 0;
    $userRating = $userRating ?? null;

    $isParticipant = false;
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isClient) {
        $isParticipant = \app\models\RouteResident::find()
            ->where(['route_id' => $model->id, 'resident_id' => Yii::$app->user->id])
            ->exists();
    }
    ?>

    <!-- Блок среднего рейтинга -->
    <div class="route-rating-container mb-4">
        <div class="d-flex align-items-center">
            <strong class="me-2">Средний рейтинг:</strong>
            <?php if ($avgRating > 0): ?>
                <span class="me-2"><?= number_format($avgRating, 1) ?></span>
                <?= StarRating::widget([
                    'name' => 'avg_rating_' . $model->id,
                    'value' => $avgRating,
                    'pluginOptions' => [
                        'size' => 'sm',
                        'readonly' => true,
                        'showClear' => false,
                        'showCaption' => false,
                        'hoverEnabled' => false,
                        'displayOnly' => true
                    ]
                ]) ?>
            <?php else: ?>
                <span class="text-muted">Нет оценок</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Форма для оценки маршрута (только для клиентов, не админов, и только если участвовал) -->
    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isClient): ?>
        <div class="rating-block p-3 rounded bg-light mb-4">
            <div class="alert alert-success alert-stars d-none text-center"></div>
            <p><strong>Ваша оценка маршрута</strong></p>
            <?php $form = ActiveForm::begin([
                'id' => 'rating-form-' . $model->id,
                'action' => Url::to(['route/rate', 'id' => $model->id]),
                'options' => ['data-route-id' => $model->id]
            ]); ?>

            <?= StarRating::widget([
                'bsVersion' => '5.x',
                'name' => 'stars',
                'value' => $userRating,
                'pluginOptions' => [
                    'readonly' => (bool)$userRating,
                    'showClear' => false,
                    'showCaption' => false,
                    'min' => 1,
                    'max' => 5,
                    'step' => 1,
                    'hoverEnabled' => !(bool)$userRating,
                    'disabled' => (bool)$userRating,
                ],
                'pluginEvents' => [
                    'rating:change' => 'function(event, value) {
                    if (value > 0) {
                        $(this).closest("form").submit();
                    }
                }'
                ]
            ]) ?>

            <?php ActiveForm::end(); ?>
        </div>
    <?php elseif (!Yii::$app->user->isGuest && Yii::$app->user->identity->isClient && !$isParticipant): ?>
        <div class="alert alert-info">
            Вы сможете оценить этот маршрут после участия в нём.
        </div>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            [
                'attribute' => 'date_start',
                'value' => Yii::$app->formatter->asDate($model->date_start, 'php:d.m.Y')
            ],
            [
                'attribute' => 'time_start',
                'value' => Yii::$app->formatter->asTime($model->time_start, 'php:H:i')
            ],
            [
                'attribute' => 'price',
                'value' => $model->price . "₽",
            ],
            [
                'attribute' => 'level_id',
                'value' => $model->level->title
            ],
            'number_participant',
            [
                'attribute' => ' Свободные места',
                'value' => $model->number_participant - $model->getRouteResidents()->count()
            ],
            'length',
            'duration',
            'outfit:ntext',
            'description:ntext',
            [
                'attribute' => 'created_at',
                'value' => Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i')
            ],
            [
                'label' => 'Изображение',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->routeImage && $model->routeImage->image) {
                        return Html::img($model->imageUrl, ['style' => 'max-width:200px;']);
                    }
                    return 'Нет изображения';
                },
            ],
        ],
    ]) ?>

</div>