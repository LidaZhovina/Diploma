<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


/** @var yii\web\View $this */
/** @var app\models\Route $model */

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
        :'' ?>
    </p>

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
                'attribute' =>' Свободные места',
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