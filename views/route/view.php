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
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'price',
                'value' => $model->price . "₽",
            ], 
            'description:ntext',
            'length',
            [
                'attribute' => 'date_start',
                'value' => Yii::$app->formatter->asDate($model->date_start, 'php:d.m.Y')
            ],
            [
                'attribute' => 'time_start',
                'value' => Yii::$app->formatter->asTime($model->time_start, 'php:H:i')
            ],
            'duration',
            'outfit:ntext',
            'number_participant',
            [
                'attribute' => 'level_id',
                'value' => $model->level->title
            ],
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