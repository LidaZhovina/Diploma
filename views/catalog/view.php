<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Room $model */

$this->title = $model->roomType->name . " " . $model->number_guests. " " . "местный";;
\yii\web\YiiAsset::register($this);
?>
<div class="room-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-end">
        <?= Html::a('Назад', ['/catalog/index', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'room_type_id',
                'value' => $model->roomType->name
            ],
            [
                'attribute' => 'price_per_day',
                'value' => $model->price_per_day . "₽",
            ],            
            'number_guests',
            'description:ntext',
            // 'number',
            // 'floor',
        ],
    ]) ?>

</div>
