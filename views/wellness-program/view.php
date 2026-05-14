<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\WellnessProgram $model */

$this->title = $model->title;

\yii\web\YiiAsset::register($this);
?>
<div class="wellness-program-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'title',
            'duration',
            'description:ntext',
            [
                'label' => 'Изображение',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->wellnessImage) {
                        return Html::img($model->imageUrl, ['style' => 'max-width:300px;']);
                    }
                    return 'Нет изображения';
                },
            ],
        ],
    ]) ?>
    

</div>